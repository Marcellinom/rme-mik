<?php

namespace App\Http\Controllers;

use App\Models\AsesmenAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function compact;
use function redirect;
use function view;

class PemeriksaanController extends Controller
{
    public function getAntrianPemeriksaanPage() {
        $data_antrian = DB::select("
            select r.no_antrian, p.nama, r.id as no_registrasi, p.no_rm, r.status from riwayat r
            join pasien p on r.status = 'Pemeriksaan' and p.no_rm = r.no_rm
            order by r.created_at asc
        ");
        return view('pemeriksaan.index', compact('data_antrian'));
    }

    public function invokePemeriksaan(Request $request) {
        $id = $request->query('id_registrasi');
        return redirect("/pemeriksaan/asesmen_awal/{$id}");
    }

    public function dataAsesmenAwal($id) {
        $res = DB::table('asesmen_awal')->where('id_riwayat', $id)->first();
        $data_asesmen_awal = [];
        $data_asesmen_awal["id_riwayat"] = $res->id_riwayat;
        $data_asesmen_awal["tanda_vital"] = [];
        $data_asesmen_awal["anamnesis"] = [];
        $data_asesmen_awal["head-to-toe"] = [];

        foreach ($res as $key => $data) {
            $group = 0;
            switch ($key) {
                case 'id_riwayat':
                case 'created_at':
                case 'updated_at':
                    continue 2;
            }
            switch ($key) {
                case 'denyut_jantung':
                case 'pernapasan':
                case 'suhu':
                case 'tingkat_kesadaran':
                case 'tekanan_darah_sistole':
                case 'tekanan_darah_distole':
                case 'berat_badan':
                case 'tinggi_badan':
                    $group = "tanda_vital";
                    break;
                case 'keluhan_utama':
                case 'riwayat_alergi_obat':
                case 'riwayat_penyakit':
                case 'riwayat_pengobatan':
                    $group = "anamnesis";
                    break;
                default:
                    $group = "head-to-toe";
            }
            $data_asesmen_awal[$group] = [...$data_asesmen_awal[$group], $key => $data];
        }

        return view('pemeriksaan.asesmen_awal', compact('data_asesmen_awal', 'id'));
    }

    public function getSoape($id) {

    }
    public function getPenunjang($id) {

    }
    public function getResumeMedis($id) {

    }
}
