<?php
/**
 * @author         Pierre-Henry Soria <hi@ph7.me>
 */

declare(strict_types = 1);

namespace Github;

require __DIR__ . '/../../vendor/autoload.php';

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
        $githubUrl = $this->getFullUrl();

        return collect(json_decode(file_get_contents($githubUrl), true));
    }

    private function lookupScore($eventType)
    {
        return collect(self::$githubScores)->get($eventType, 1);
    }

    private function getFullUrl(): string
    {
        return sprintf(self::GITHUB_API_URL . 'users/%s/events', $this->username);
    }
}