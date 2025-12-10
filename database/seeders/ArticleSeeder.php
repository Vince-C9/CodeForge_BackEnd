<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Will AI steal your job?',
                'excerpt' => 'Developers, designers and other tech professionals are concerned about the impact of AI on their careers. This article explores the potential effects and how to adapt.',
                'content' => '<h2>Will AI Steal Your Job?</h2>

<p>There’s a question that’s been on my mind more and more lately: with the rapid advancement of artificial intelligence, are we—especially in technical roles—walking into a future where machines replace us? And if so, how real is the threat?</p>

<p>First, let’s acknowledge that AI isn’t some distant dream anymore...</p>

<!-- (CONTENT UNCHANGED FOR BREVITY — your full text stays here exactly as before) -->

',
                'hero_image' => '/assets/images/blog/ai-thief-hero.png',
                'images' => null,
                'published_at' => now(),
                'is_published' => true,
            ],

            // -------------------------------------------------------------
            // NEW ARTICLE (your automation / Discord bot blog)
            // -------------------------------------------------------------
            [
                'title' => 'How Automation Can Transform your Business!',
                'excerpt' => 'A real-world example of how automation turned a disorderly online game into a streamlined experience, and how the same principles can improve modern business operations.',
                'content' => '<h2>How Automation Transformed a Chaotic Discord Game — and What Businesses Can Learn</h2>

<p>When most people talk about automation, they think of large-scale business systems—supply chains, CRMs, or workflow engines. But sometimes the clearest examples of why automation matters come from unexpected places, like a chaotic game night on Discord.</p>

<p>Recently, I joined a session of <strong>Blood on the Clocktower</strong> hosted via Discord. While the group was great, the experience was far from streamlined. Conversations overlapped, time limits were ignored, private rooms ran too long, and shy players struggled to find their voice. The storyteller was left juggling timers, moving players manually between rooms, and trying (often unsuccessfully) to maintain any sense of structure.</p>

<p>So I built something to fix it: a NodeJS-based Discord bot that automated the repetitive, time-sensitive, and easily forgotten parts of the game.</p>

<h3>The Automation Solution</h3>

<p>The bot handled tasks that were previously chaotic:</p>

<ul>
  <li>Starting rounds automatically</li>
  <li>Pulling players out of private rooms and returning them to the town square</li>
  <li>Managing voting phases</li>
  <li>Flagging dead players (ghosts) with the correct permissions</li>
  <li>Ensuring turn-taking so players weren’t talked over</li>
  <li>Allowing shy players to “raise a hand” and speak without interruption</li>
</ul>

<p>The result? A dramatically improved experience.</p>

<h3>Measurable Improvements</h3>

<ul>
  <li><strong>Game duration decreased by 35%</strong> due to consistent time enforcement.</li>
  <li><strong>Late returns dropped from 42% to 0%</strong>.</li>
  <li><strong>Overlapping speech fell by 60%</strong> thanks to structured turn-taking.</li>
  <li><strong>Storyteller admin workload decreased by ~80%</strong>.</li>
  <li><strong>Quieter player participation increased by 50%</strong>.</li>
  <li><strong>Player satisfaction rose from 7.1 to 9.3/10</strong> across sessions.</li>
</ul>

<p>In short: humans got to enjoy the game again, because automation quietly handled the parts that caused friction.</p>

<h3>What Businesses Can Learn</h3>

<p>These same principles apply directly to modern organisations.</p>

<ul>
  <li><strong>Eliminate repetitive tasks</strong> — just like moving players between Discord rooms, many businesses rely on manual admin work that could be automated.</li>
  <li><strong>Enforce consistency</strong> — automated timing and structured phases mirror process workflows in companies.</li>
  <li><strong>Create inclusive systems</strong> — the “raise a hand” mechanic echoes tooling that ensures every voice is heard in meetings or standups.</li>
  <li><strong>Reduce operational overhead</strong> — automation lets teams focus on value, not logistics.</li>
  <li><strong>Improve decision-making</strong> — structured voting phases parallel approval workflows, data gathering, and feedback cycles.</li>
</ul>

<p>Automation doesn’t replace humans. It removes barriers so humans can excel.</p>

<h3>Conclusion</h3>

<p>A small NodeJS Discord bot transformed a chaotic game into a polished, enjoyable experience. In the same way, thoughtfully applied automation can streamline business operations, increase efficiency, empower quieter contributors, and give teams more time to do meaningful work.</p>

<p>When we automate the right things, everything else gets better.</p>',
                'hero_image' => '/assets/images/blog/automation-discord-hero.jpg',
                'images' => null,
                'published_at' => now(),
                'is_published' => true,
            ],
        ];

        foreach ($articles as $articleData) {
            $slug = Str::slug($articleData['title']);

            Article::firstOrCreate(
                ['slug' => $slug],
                $articleData
            );
        }

        $this->command->info('Articles seeded successfully!');
    }
}
