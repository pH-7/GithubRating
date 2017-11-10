<?php
/**
 * @author         Pierre-Henry Soria <hi@ph7.me>
 */

declare(strict_types = 1);

namespace Github;

require __DIR__ . '/../collection.laravel.php';

class Score
{
    private const GITHUB_API_URL = 'https://api.github.com/';

    /** @var string */
    private $username;

    /** @var array */
    private static $githubScores = [
        'PushEvent' => 5,
        'CreateEvent' => 4,
        'IssuesEvent' => 3,
        'CommitCommentEvent' => 2,
    ];

    private function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function forUser(string $username)
    {
        return (new self($username))->score();
    }

    private function score()
    {
        $this->events()->pluck('type')->map(function ($eventType) {
            return $this->lookupScore($eventType);
        })->sum();
    }

    private function events()
    {
        $url = sprintf(self::GITHUB_API_URL . 'users/%s/events', $this->username);

        return collect(json_decode(file_get_contents($url), true));
    }

    private function lookupScore($eventType)
    {
        return collect(self::$githubScores)->get($eventType, 1);
    }
}