<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            ['name' => 'ALAMO','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'ARMAGOSA','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'BEATTY','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'BLUEDIAM','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'BOULDERC','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'CALIENTE','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'CALNEVAR','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'COLDCRK','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'ELY','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'GLENDALE','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'GOODSPRG','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'HENDERSON','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'INDIANSP','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'JEAN','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'LASVEGAS','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'LAUGHLIN','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'LOGANDAL','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'MCGILL','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'MESQUITE','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'MOAPA','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'MTNSPRG','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'NORTHLAS','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'OTHER','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'OVERTON','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'PAHRUMP','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'PALMGRDNS','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'PANACA','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'PIOCHE','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'SANDYVLY','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'SEARCHLT','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'TONOPAH','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
            ['name' => 'URSINE','status'=>'0','total'=>'0','inserted' => '0','created_at'=>\Carbon\Carbon::now(),'updated_at'=>\Carbon\Carbon::now()],
        ]);
    }
}
