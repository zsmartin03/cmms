<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

return [
    'tables' => [
        'comments' => 'comments',
        'reactions' => 'reactions',
        'users' => 'users', // The table that will be used to get the authenticated user
    ],

    'models' => [
        'comment' => \Coolsam\NestedComments\Models\Comment::class,
        'reaction' => \Coolsam\NestedComments\Models\Reaction::class,
        'user' => env('AUTH_MODEL', 'App\Models\User'), // The model that will be used to get the authenticated user
    ],

    'policies' => [
        'comment' => null,
        'reaction' => null,
    ],
    'allowed-reactions' => [
        '👍' => 'thumbs up', // thumbs up
        '👎' => 'thumbs down', // thumbs down
        '❤️' => 'heart', // heart
        '😂' => 'laughing', // laughing
        '😮' => 'surprised', // surprised
        '😢' => 'crying', // crying
        '💯' => 'hundred points', // angry
        '🔥' => 'fire', // fire
        '🎉' => 'party popper', // party popper
        '🚀' => 'rocket', // rocket
    ],
    'allow-all-reactions' => env('ALLOW_ALL_REACTIONS', false), // Allow any other emoji apart from the ones listed above
    'allow-multiple-reactions' => env('ALLOW_MULTIPLE_REACTIONS', false), // Allow multiple reactions from the same user
    'allow-guest-reactions' => env('ALLOW_GUEST_REACTIONS', false), // Allow guest users to react
    'allow-guest-comments' => env('ALLOW_GUEST_COMMENTS', false), // Allow guest users to comment
    'closures' => [
        'getUserNameUsing' => fn(Authenticatable | Model $user) => $user->getAttribute('name'),
        'getUserAvatarUsing' => function ($user) {
            if (!is_object($user)) {
                $foundUser = User::where('name', $user)->first();
                if ($foundUser && method_exists($foundUser, 'getFilamentAvatarUrl')) {
                    $url = $foundUser->getFilamentAvatarUrl();
                    if ($url) {
                        return $url;
                    }
                }
            } elseif (method_exists($user, 'getFilamentAvatarUrl')) {
                $url = $user->getFilamentAvatarUrl();
                if ($url) {
                    return $url;
                }
            }
            $attr = method_exists($user, 'getAttribute') ? $user->getAttribute('avatar_url') : null;
            if ($attr) {
                return $attr;
            }
            return app(\Coolsam\NestedComments\NestedComments::class)->geDefaultUserAvatar($user);
        },
        'getMentionsUsing' => fn(
            string $query,
            Model $commentable
        ) => app(\Coolsam\NestedComments\NestedComments::class)->getCurrentThreadUsers($query, $commentable),
    ],
    'mentions' => [
        'items-placeholder' => 'Search users by name or email address',
        'empty-items-message' => 'No users found',
    ],
];
