<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class M_apriori extends CI_Model {

    // insert waktu
    public function get($filter1, $filter2)
    {
        if(empty($filter1) && empty($filter2))
        {
        $query = $this->db->query("SELECT diagnosa_pasien.jenis_kelamin as jk, diagnosa_pasien.kategori_usia as usia, diagnosa_pasien.diagnosa diag, concat(diagnosa_pasien.jenis_kelamin, diagnosa_pasien.kategori_usia, diagnosa_pasien.diagnosa) as jk_katUsia, COUNT(diagnosa_pasien.id_diagnosa_pasien) as total_kasus, 
        ROUND((COUNT(diagnosa_pasien.id_diagnosa_pasien) / (SELECT COUNT(*) FROM diagnosa_pasien))*100, 1) as support,
        ROUND((COUNT(diagnosa_pasien.id_diagnosa_pasien) / (SELECT COUNT(*) FROM diagnosa_pasien WHERE diagnosa_pasien.jenis_kelamin=jk))*100, 1) as confidence
        FROM diagnosa_pasien
        GROUP BY concat(diagnosa_pasien.jenis_kelamin, diagnosa_pasien.kategori_usia, diagnosa_pasien.diagnosa)")->result();

        return $query;
        } else {
      
      $query = $this->db->query("SELECT jk, usia, diag, support, confidence FROM(
        SELECT diagnosa_pasien.jenis_kelamin as jk, diagnosa_pasien.kategori_usia as usia, diagnosa_pasien.diagnosa diag, concat(diagnosa_pasien.jenis_kelamin, diagnosa_pasien.kategori_usia, diagnosa_pasien.diagnosa) as jk_katUsia, COUNT(diagnosa_pasien.id_diagnosa_pasien) as total_kasus, 
        ROUND((COUNT(diagnosa_pasien.id_diagnosa_pasien) / (SELECT COUNT(*) FROM diagnosa_pasien))*100, 1) as support,
        ROUND((COUNT(diagnosa_pasien.id_diagnosa_pasien) / (SELECT COUNT(*) FROM diagnosa_pasien WHERE diagnosa_pasien.jenis_kelamin=jk))*100, 1) as confidence
        FROM diagnosa_pasien
        GROUP BY concat(diagnosa_pasien.jenis_kelamin, diagnosa_pasien.kategori_usia, diagnosa_pasien.diagnosa))t1 
        WHERE support >= $filter1 AND confidence >= $filter2")->result();

        return $query; 
        }
    }

    public function totalData()
    {
        $query = $this->db->query("SELECT COUNT(diagnosa_pasien.id_diagnosa_pasien) as data_pasien FROM diagnosa_pasien")->result();
        return $query;
    }

    public function terakhirUpload()
    {
        $query = $this->db->query("SELECT DATE_FORMAT(created, '%d %M %Y') as upload FROM diagnosa_pasien
        GROUP BY created
        ORDER BY created DESC
        LIMIT 1")->result();
        return $query;
    }

    public function totalUser()
    {
        $query = $this->db->query("SELECT COUNT(user.userId) as user FROM user")->result();
        return $query;
    }

    public function jk()
    {
        $query = $this->db->query("SELECT diagnosa_pasien.jenis_kelamin as jenis_kelamin, COUNT(diagnosa_pasien.id_diagnosa_pasien) as totalJK
        FROM diagnosa_pasien
        GROUP BY diagnosa_pasien.jenis_kelamin")->result();
        return $query;
    }

    public function topPenyakit()
    {
        $query = $this->db->query("SELECT diagnosa_pasien.diagnosa as diag, COUNT(diagnosa_pasien.id_diagnosa_pasien)as totalKasusDiag
        FROM diagnosa_pasien
        GROUP BY diagnosa_pasien.diagnosa
        ORDER BY totalKasusDiag DESC
        LIMIT 10")->result();
        return $query;
    }

    public function usia()
    {
        $query = $this->db->query("SELECT diagnosa_pasien.kategori_usia as totUsia, COUNT(diagnosa_pasien.id_diagnosa_pasien)as totalKasusUsia
        FROM diagnosa_pasien
        GROUP BY diagnosa_pasien.kategori_usia
        ORDER BY kategori_usia = 'Balita', kategori_usia = 'Kanak-Kanak',  kategori_usia = 'Remaja Awal', kategori_usia = 'Remaja Akhir', kategori_usia = 'Dewasa Awal', kategori_usia = 'Dewasa Akhir', kategori_usia = 'Lansia Awal', kategori_usia = 'Lansia Akhir', 'Manula'")->result();
        return $query;
    }
    
    public function sebaranRuanganKelas($kelas)
    {
        if(empty($kelas))
        {
        $query = $this->db->query("SELECT COUNT(diagnosa_pasien.id_diagnosa_pasien) as totalData, diagnosa_pasien.ruangan as ruanganKelas
        FROM diagnosa_pasien
        WHERE diagnosa_pasien.kelas='I'
        GROUP BY diagnosa_pasien.ruangan")->result();
        return $query;
        } else{
        $query = $this->db->query("SELECT COUNT(diagnosa_pasien.id_diagnosa_pasien) as totalData, diagnosa_pasien.ruangan as ruanganKelas
        FROM diagnosa_pasien
        WHERE diagnosa_pasien.kelas='$kelas'
        GROUP BY diagnosa_pasien.ruangan")->result();
        return $query;
        }
    }

    public function kelas()
    {
        $query = $this->db->query("SELECT kelas FROM diagnosa_pasien
        GROUP BY kelas")->result();
        return $query;
    }
}