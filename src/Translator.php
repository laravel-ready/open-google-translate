<?php

namespace LaravelReady\OpenGoogleTranslate;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Date;

class Translator
{
    /**
     * Translate given text from source language to target language
     *
     * @param string $text
     * @param string $sourceLanguage two-letter language code (ex. en)
     * @param string $targetLanguage two-letter language code (ex. tr)
     *
     * @return string|null
     */
    public static function translate(string $text, string $sourceLang, string $translateLang): ?string
    {
        $text = trim($text);
        $contentHash = hash('md5', $text);
        $cahceKey = "open:translate:keyword:{$contentHash}:{$sourceLang}:{$translateLang}";
        $isCachingenabled = Config::get('open-google-translate.use_cache', true);

        if ($isCachingenabled === true && Cache::has($cahceKey)) {
            return Cache::get($cahceKey);
        }

        if (Config::get('open-google-translate.sleep_seconds') > 0) {
            sleep(Config::get('open-google-translate.sleep_seconds', 0));
        }

        $text = urlencode($text);
        $sourceLang = strtolower($sourceLang);
        $translateLang = strtolower($translateLang);

        $apiUrl = "https://translate.googleapis.com/translate_a/single?client=dict-chrome-ex&dt=t&sl={$sourceLang}&tl={$translateLang}&q={$text}";

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',
        ];

        $response = Http::withHeaders($headers)
            ->withoutVerifying()
            ->get($apiUrl);

        if ($response->successful()) {
            $response = $response->json();

            if (isset($response[0][0][0]) && !empty($response[0][0][0])) {
                if ($isCachingenabled === true) {
                    Cache::put($cahceKey, $response[0][0][0], Date::now()->addMinutes(
                        Config::get('open-google-translate.cache_minutes', 60 * 24 * 7)
                    ));
                }

                return $response[0][0][0];
            }
        }

        return null;
    }
}
