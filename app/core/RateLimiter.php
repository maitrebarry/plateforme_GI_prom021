<?php

/**
 * Limiteur de debit simple base sur la session (fenetre glissante).
 *
 * Suffisant comme premiere barriere contre l'abus des endpoints IA
 * (couts/quota Hugging Face). Pour une protection robuste multi-clients,
 * remplacer le stockage session par un stockage partage (fichier/Redis)
 * cle par adresse IP.
 *
 * Usage :
 *   if (!RateLimiter::allow('ai', 15, 60)) { // 15 requetes / 60 s
 *       // repondre 429
 *   }
 */
class RateLimiter
{
    private const STORE_KEY = '__rate_limiter';

    public static function allow(string $key, int $maxHits, int $windowSeconds): bool
    {
        $now = time();
        $hits = $_SESSION[self::STORE_KEY][$key] ?? [];

        if (!is_array($hits)) {
            $hits = [];
        }

        // Ne garder que les hits dans la fenetre courante.
        $hits = array_values(array_filter($hits, static function ($timestamp) use ($now, $windowSeconds) {
            return is_int($timestamp) && ($now - $timestamp) < $windowSeconds;
        }));

        if (count($hits) >= $maxHits) {
            $_SESSION[self::STORE_KEY][$key] = $hits;
            return false;
        }

        $hits[] = $now;
        $_SESSION[self::STORE_KEY][$key] = $hits;

        return true;
    }
}
