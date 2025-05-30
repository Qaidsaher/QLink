<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create a known user with specific credentials
        $knownUser = User::factory()->create([
            'name' => 'saher qaid',
            'username' => 'qaidsaher',                     // known username
            'email'    => 'saherqaid@gmail.com',         // known email
            'password' => bcrypt('password'),        // known password (use "secretpassword" to log in)
        ]);

        // Create 9 additional users for a total of 10 users, including the known user
        $otherUsers = User::factory(9)->create();

        // Merge the known user with the other users into one collection
        $allUsers = collect([$knownUser])->merge($otherUsers);

        // For each user (total 10), create 10 posts
        $allUsers->each(function ($user) use ($allUsers) {
            Post::factory(10)
                ->for($user)
                ->create()
                ->each(function ($post) use ($allUsers) {
                    // For each post, create 10 comments by randomly selecting an existing user as the author
                    Comment::factory(10)
                        ->for($post)
                        ->create([
                            'user_id' => $allUsers->random()->id,
                        ]);

                    // For each post, create 10 likes with a random user (ensuring uniqueness via the unique constraint in the migration)
                    Like::factory(10)
                        ->for($post)
                        ->create([
                            // 'user_id' => $allUsers->random()->id,
                        ]);
                });
        });

        // Create an additional user who will follow 10 random other users
        $userWithFollowing = User::factory()->create([
            'username' => 'user_with_following',
        ]);

        // Get all users except the one who will follow others
        $usersForFollowing = User::where('id', '!=', $userWithFollowing->id)->get();

        // If there are at least 10 users available, attach 10 random ones to the following relationship
        if ($usersForFollowing->count() >= 10) {
            $following = $usersForFollowing->random(10);
            foreach ($following as $followedUser) {
                $userWithFollowing->following()->attach($followedUser->id);
            }
        }
    }
}
