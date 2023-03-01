<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Blogs;

class ScheduleBlogPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:schedule-post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all blogs which are scheduled and post them.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $end_time = now()->format('Y-m-d H:i:s');
        $start_time = date('Y-m-d H:i:s', strtotime('-35 minutes', strtotime($end_time)));

        $scheduled_blogs = Blogs::whereBetween('scheduled_at', [$start_time, $end_time])
                                ->where('status', '3')
                                ->get();
            
        if( $scheduled_blogs->count() > 0 ){
            foreach($scheduled_blogs as $blog){
                $blog->status = '1';
                $blog->save();
            }
        }
    }
}
