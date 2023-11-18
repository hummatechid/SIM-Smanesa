<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ViolationType;

class ViolationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_sikap = [
            [
                "name" => "Membawa atau bermain kartu remi, domino, dan sejenisnya dalam bentuk apapun di lingkungan sekolah",
                "score" => 10
            ], [
                "name" => "Bermain bola dan sejenisnya di koridor dan di dalam kelas",
                "score" => 10
            ], [
                "name" => "Menyontek",
                "score" => 10
            ], [
                "name" => "Melindungi teman yang melanggar tatib sekolah",
                "score" => 20
            ],  [
                "name" => "Merayakan ulang tahun berlebihan",
                "score" => 40
            ], [
                "name" => "Menyalahgunakan administrasi keuangan sekolah",
                "score" => 25
            ], [
                "name" => "Membawa atau membunyikan petasan",
                "score" => 30
            ], [
                "name" => "Membuat surat izin palsu",
                "score" => 40
            ], [
                "name" => "Menyalahgunakan media sosial yang merugikan pihak lain yang berhubungan dengan sekolah",
                "score" => 100
            ], [
                "name" => "Mengikuti aliran/perkumpulan/geng terlarang/komunitas LGBT dan radikalisme",
                "score" => 150
            ], [
                "name" => "Membawa, memakai, mengedarkan narkoba dan minuman keras",
                "score" => 250
            ], [
                "name" => "Menyerang guru atau personil sekolah",
                "score" => 250
            ], [
                "name" => "Mencuri / merampas baik didalam maupun di luar sekolah",
                "score" => 200
            ], [
                "name" => "Melakukan perbuatan asusila atau perbuatan lain yang menyimpang dari norma-norma kesusilaan berpelukan, berciuman baik di dalam maupun di luar sekolah",
                "score" => 100
            ], [
                "name" => "Melakukan Perbuatan asusila yang berupa pelecehan seksual yang melanggar hukum",
                "score" => 100
            ], [
                "name" => "Membawa senjata tajam, senjata api dan sejenisnya yang tidak ada kaitannya dengan PBM",
                "score" => 150
            ], [
                "name" => "Hamil atau menghamili diluar nikah",
                "score" => 250
            ], [
                "name" => "Melakukan pernikahan sah maupun siri selama menjadi peserta didik",
                "score" => 250
            ], [
                "name" => "Terlibat perkelahian / tawuran dan main hakim sendiri baik di dalam maupun di luar sekolah",
                "score" => 150
            ], [
                "name" => "Berjudi di lingkungan sekolah",
                "score" => 150
            ], [
                "name" => "Membawa rokok / vape (rokok elektrik) / merokok saat masih mengenakan seragam sekolah",
                "score" => 100
            ], [
                "name" => "Memalsukan stempel sekolah, edaran sekolah atau tanda -tangan kepala sekolah, guru, wali kelas, karyawan ",
                "score" => 250
            ], [
                "name" => "Membuat pernyataan palsu baik tertulis maupun lisan",
                "score" => 150
            ], [
                "name" => "Melakukan pemerasan / mengancam / mengintimidasi / bullying kepada warga sekolah ( teman, karyawan, guru dan kepala sekolah)",
                "score" => 75
            ], [
                "name" => "Terlibat tindakan kriminal, mencemarkan nama baik sekolah (peserta didik lain, karyawan, guru dan kepala sekolah)",
                "score" => 250
            ], [
                "name" => "Menyalahgunakan fasilitas sekolah dan merusak  sarana dan prasarana sekolah",
                "score" => 50
            ], [
                "name" => "Membawa, menyimpan menggandakan, mengedarkan buku bacaan CD,VCD, flash disk, hard disk, gambar teks porno, HP yang memiliki file porno atau sesuatu yang berbau pronografi dan pornoaksi yang terdeteksi pihak sekolah",
                "score" => 200
            ], [
                "name" => "Menyalahgunakan HP dan sejenisnya yang dapat mengganggu PBM",
                "score" => 50
            ], [
                "name" => "Terjerat razia pelajar oleh pihak yang berwajib",
                "score" => 30
            ], [
                "name" => "Mencoret-coret, merobek, mengubah, menghilangkan dan merusak  buku tatib dengan sengaja",
                "score" => 100
            ], [
                "name" => "Melompat pagar sekolah dan jendela kelas",
                "score" => 40
            ], [
                "name" => "Tidak menyampaikan/mengubah surat edaran atau undangan untuk orang tua",
                "score" => 20
            ], [
                "name" => "Membuang sampah dan meludah di sembarang tempat",
                "score" => 10
            ], [
                "name" => "Makan dan minum pada saat PBM tanpa seizing guru",
                "score" => 20
            ], [
                "name" => "Berada di kantin sekolah pada saat PBM tanpa seijin guru",
                "score" => 10
            ], [
                "name" => "Bertindak tidak sopan terhadap warga sekolah (teman, karyawan, guru dan kepala sekolah)",
                "score" => 50
            ], [
                "name" => "Mengganggu proses PBM (clometan, membuat gaduh, atau sejenisnya)",
                "score" => 10
            ], [
                "name" => "Menggunakan fasilitas guru dan tenaga kependidikan tanpa seizing guru",
                "score" => 20
            ], [
                "name" => "Tidur dikelas saat pelajaran berlangsung",
                "score" => 10
            ], [
                "name" => "Membawa sampah dari luar area sekolah dan membuang di dalam lingkungan sekolah",
                "score" => 20
            ],
        ];

        $data_kerapian = [
            [
                "name" => "Memakai Celana atau rok bawah tidak dikelim",
                "score" => 10
            ], [
                "name" => "Memakai Celana atau rok dengan motif modifikasi",
                "score" => 20
            ], [
                "name" => "Tidak memakai atribut dan kelengkapan seragam sesuai ketentuan sekolah",
                "score" => 10
            ], [
                "name" => "Pakaian dan atribut di corat-coret",
                "score" => 10
            ], [
                "name" => "Tidak berpakaian rapi atau baju dikeluarkan",
                "score" => 10
            ], [
                "name" => "Tidak memakai kaos kaki sesuai dengan ketentuan sekolah",
                "score" => 10
            ], [
                "name" => "Tidak memakai sepatu sesuai dengan ketentuan sekolah (kombinasi putih max 20%) ",
                "score" => 20
            ], [
                "name" => "Berpakaian / bersolek dan memakai perhiasan berlebihan",
                "score" => 20
            ], [
                "name" => "Tidak memakai ikat pinggang dan memakai ikat pinggang tidak sesuai",
                "score" => 10
            ], [
                "name" => "Memanjangkan dan mengecat kuku, rambut dicat, menggunakan bulu mata palsu untuk peserta didik putri",
                "score" => 20
            ], [
                "name" => "Memakai kalung, binggel dan gelang untuk siswa putra",
                "score" => 10
            ], [
                "name" => "Bentuk seragam tidak sesuai dengan ketentuan yang berlaku",
                "score" => 20
            ], [
                "name" => "Memakai anting, bertindik, bertato, dan potongan rambut tidak sesuai ukuran pelajar (panjang, gondrong, dan dicat)",
                "score" => 20
            ], [
                "name" => "Membawa dan menggunakan sandal maupun sejenisnya selain sepatu, di dalam ataupun di luar kelas (lingkungan sekolah)",
                "score" => 10
            ],
        ];

        $data_kerajinan = [
            [
                "name" => "Terlambat masuk sekolah 1-5 kali",
                "score" => 10
            ], [
                "name" => "Terlambat masuk sekolah 6-10 kali",
                "score" => 20
            ], [
                "name" => "Terlambat masuk sekolah diatas 11 kali",
                "score" => 30
            ], [
                "name" => "Tidak mengikuti upacara tanpa ijin",
                "score" => 20
            ], [
                "name" => "Mengikuti upacara dengan tidak tertib (dikeluarkan dari barisan) ",
                "score" => 10
            ], [
                "name" => "Meninggalkan jam pelajaran tanpa ijin",
                "score" => 10
            ], [
                "name" => "Tidak masuk sekolah tanpa keterangan 1-3 hari",
                "score" => 20
            ], [
                "name" => "Tidak masuk sekolah tanpa keterangan 4-6 hari",
                "score" => 25
            ], [
                "name" => "Tidak masuk sekolah tanpa keterangan diatas 7 hari",
                "score" => 30
            ], [
                "name" => "Tidak membawa, mengerjakan tugas yang telah diberikan",
                "score" => 20
            ], [
                "name" => "Tidak membawa buku sesuai jadwal",
                "score" => 10
            ], [
                "name" => "Tidak mengikuti kegiatan kepramukaan khusus kelas X",
                "score" => 10
            ], [
                "name" => "Tidak mengikuti kegiatan yang sudah ditentukan sekolah",
                "score" => 20
            ], [
                "name" => "Datang terlambat pada waktu pergantian jam pelajaran",
                "score" => 10
            ], [
                "name" => "Meninggalkan kelas tanpa seijin guru",
                "score" => 10
            ], [
                "name" => "Tidak melaksanakan piket",
                "score" => 10
            ], [
                "name" => "Meninggalkan buku pelajaran dengan sengaja didalam kelas",
                "score" => 20
            ], [
                "name" => "Meninggalkan atribut, sepatu dengan sengaja didalam kelas",
                "score" => 10
            ], [
                "name" => "Pulang sebelum waktunya tanpa seijin guru, piket atau waka",
                "score" => 20
            ],
        ];

        $datas = array_merge($data_sikap, $data_kerapian, $data_kerajinan);

        foreach($datas as $data) {
            ViolationType::create($data);
        }
    }
}
