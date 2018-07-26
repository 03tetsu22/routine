<?php

use Illuminate\Database\Seeder;

class TableSeedes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $space = ['ベランダ', 'カフェスペース', '業務スペース', '受付裏スペース', '受付', '会議室', '給湯室', 'トイレ'];
        foreach ($space as $space) {
            DB::table('m_space')->insert('space');
        }
        $point = [1, 2, 3];
        foreach ($point as $point) {
            DB::table('m_point')->insert('spoint');
        }
        $frequency = ['一日一度', '週一度', '月一度', '適宜判断'];
        foreach ($frequency as $frequency) {
            DB::table('m_frequency')->insert('frequency');
        }
        DB::table('m_routine')->insert([
            [
                'routine_name' => '吸い殻処理',
                'point' => 2,
                'm_space_id' => 1,
                'm_frequency_id' => 4
            ],
            [
                'routine_name' => 'イスの水拭き',
                'point' => 2,
                'm_space_id' => 2,
                'm_frequency_id' => 4,
            ],
            [
                'routine_name' => '文房具補充',
                'point' => 2,
                'm_space_id' => 4,
                'm_frequency_id' => 4,
            ],
            [
                'routine_name' => '玄関の掃除',
                'point' => 3,
                'm_space_id' => 5,
                'm_frequency_id' => 3,
            ],
            [
                'routine_name' => 'ホワイトボードの水拭き',
                'point' => 2,
                'm_space_id' => 6,
                'm_frequency_id' => 3,
            ],
            [
                'routine_name' => '給湯室の掃除',
                'point' => 3,
                'm_space_id' => 7,
                'm_frequency_id' => 2,
            ],
            [
                'routine_name' => '便器の掃除',
                'point' => 3,
                'm_space_id' => 8,
                'm_frequency_id' => 2,
            ]
        ]);
    }
}
