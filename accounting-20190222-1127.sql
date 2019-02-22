-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Feb 22, 2019 at 05:27 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAKTIVA` ()  BEGIN
set @a:=0;
SELECT lr.id,lr.nama,lr.saldoAwal,jDebet,jKredit,lr.SaldoAkhir,@a:=@a + lr.SaldoAkhir as labarugi
FROM
(

#PENDAPATAN PENJUALAN
SELECT a.id,a.kode,
a.nama,SUM(jd.debet) as jDebet,SUM(jd.kredit) as jKredit,s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0) ELSE
IFNULL(s.saldo,0)-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id in(1,5,6,7,9)
group BY a.id
#POTONGAN PENJUALAN dan RETURN
UNION
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode >= 420
group BY a.id

) as lr;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `splr` ()  BEGIN
set @a:=0;
SELECT lr.id,lr.nama,lr.saldoAwal,lr.SaldoAkhir,@a:=@a + lr.SaldoAkhir as labarugi
FROM
(

#PENDAPATAN PENJUALAN
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode <= 410
group BY a.id

UNION
#PENDAPATAN Potongan dan Return (mengurangi/-)
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode >= 420
group BY a.id
UNION
#BIAYA Atas Pendapatan
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=5 
group BY a.id
UNION
#Pengeluaran Operasional
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=6 
group BY a.id
UNION
#Pengeluaran Non Operasional
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=7 
group BY a.id
UNION
#Pendapatan Lain
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=8 
group BY a.id
UNION
#Pengeluaran Lain
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=9 
group BY a.id

) as lr;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spNeraca` ()  BEGIN
set @a:=0;
set @b:=0;
SELECT lr.id,lr.nama,lr.saldoAwal,jDebet,jKredit,lr.SaldoAkhir,@a:=@a + lr.SaldoAkhir as labarugi
FROM
(

#PENDAPATAN PENJUALAN
SELECT a.id,a.kode,
a.nama,SUM(jd.debet) as jDebet,SUM(jd.kredit) as jKredit,s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0) ELSE
IFNULL(s.saldo,0)-(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id in(1,5,6,7,9)
group BY a.id
#POTONGAN PENJUALAN dan RETURN
UNION
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode >= 420
group BY a.id

) as lr
union
#set @a:=0;
SELECT lr.id,lr.nama,lr.saldoAwal,jDebet,jKredit,lr.SaldoAkhir,@b:=@b + lr.SaldoAkhir as labarugi
FROM
(

#PENDAPATAN PENJUALAN
SELECT a.id,a.kode,
a.nama,SUM(jd.debet) as jDebet,SUM(jd.kredit) as jKredit,s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(s.saldo,0)-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id in(2,3,8)
group BY a.id
UNION
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode < 420
group BY a.id
) as lr;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spPASIVA` ()  BEGIN
set @a:=0;
SELECT lr.id,lr.nama,lr.saldoAwal,jDebet,jKredit,lr.SaldoAkhir,@a:=@a + lr.SaldoAkhir as labarugi
FROM
(

#PENDAPATAN PENJUALAN
SELECT a.id,a.kode,
a.nama,SUM(jd.debet) as jDebet,SUM(jd.kredit) as jKredit,s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
IFNULL(s.saldo,0)-(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id in(2,3,8)
group BY a.id
UNION
SELECT a.id,a.kode,
a.nama,SUM(jd.debet),SUM(jd.kredit),s.saldo as saldoAwal,
CASE WHEN
IFNULL(SUM(jd.debet),0) > IFNULL(SUM(jd.kredit),0)
THEN
(IFNULL(SUM(jd.debet),0)- IFNULL(SUM(jd.kredit),0) + IFNULL(s.saldo,0)) ELSE
(IFNULL(SUM(jd.kredit),0)-IFNULL(SUM(jd.debet),0))+IFNULL(s.saldo,0)
END as saldoAkhir
from jurnal as j
join jurnald as jd on jd.jurnal_id=j.id
RIGHT JOIN akun as a on a.id=jd.akun_id
JOIN subgrup as sg on sg.id=a.subgrup_id
join `grup` as g on g.id=sg.grup_id
left join saldoawal as s on s.akun_id=a.id and s.periode_id=1
where g.id=4 and a.kode < 420
group BY a.id
) as lr;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `subgrup_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `matauang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `kode`, `nama`, `subgrup_id`, `user_id`, `matauang_id`) VALUES
(1, '110-10', 'Kas Kecil', 1, NULL, 61),
(2, '110-20', 'Kas', 1, NULL, 61),
(3, '110-30', 'Kas (USD)', 1, NULL, 146),
(4, '120-10', 'Bank', 2, NULL, 61),
(5, '120-20', 'Bank (USD)', 2, NULL, 146),
(6, '120-21', 'bang kecil', 2, NULL, 61),
(7, '130-10', 'Piutang Giro', 3, NULL, 61),
(8, '130-20', 'Piutang Usaha', 3, NULL, 61),
(9, '130-30', 'Piutang Usaha (USD)', 3, NULL, 146),
(10, '130-40', 'Cadangan Kerugian Piutang', 3, NULL, 61),
(11, '130-50', 'Piutang Non Usaha', 3, NULL, 61),
(12, '140-10', 'Persediaan 1', 4, NULL, 61),
(13, '140-20', 'Persediaan 2', 4, NULL, 61),
(14, '140-30', 'Persediaan 3', 4, NULL, 61),
(15, '140-40', 'Persediaan 4', 4, NULL, 61),
(16, '150-10', 'Pajak Dibayar di Muka', 5, NULL, 61),
(17, '150-20', 'Asuransi Dibayar di Muka', 5, NULL, 61),
(18, '160-10', 'Investasi Saham', 6, NULL, 61),
(19, '160-20', 'Investasi Obligasi', 6, NULL, 61),
(20, '170-10', 'Tanah', 7, NULL, 61),
(21, '170-20', 'Bangunan', 7, NULL, 61),
(22, '170-21', 'Akumulasi Penyusutan Bangunan', 7, NULL, 61),
(23, '170-30', 'Mesin dan Peralatan Kantor', 7, NULL, 61),
(24, '170-31', 'Akumulasi Penyusutan Mesin dan Peralatan', 7, NULL, 61),
(25, '170-40', 'Mebel dan Alat Tulis Kantor', 7, NULL, 61),
(26, '170-41', 'Akumulasi Penyusutan Mebel dan ATK', 7, NULL, 61),
(27, '170-50', 'Kendaraan', 7, NULL, 61),
(28, '170-51', 'Akumulasi Penyusutan Kendaraan', 7, NULL, 61),
(29, '170-70', 'Harta Lainnya', 7, NULL, 61),
(30, '170-71', 'Akumulasi Penyusutan Harta Lainnya', 7, NULL, 61),
(31, '180-10', 'Hak Merek', 8, NULL, 61),
(32, '180-20', 'Hak Cipta', 8, NULL, 61),
(33, '180-30', 'Good Will', 8, NULL, 61),
(34, '190-20', 'Biaya Pra Operasi dan Operasi', 9, NULL, 61),
(35, '190-21', 'Akumulasi Amortisasi Pra Operasi dan Operasi', 9, NULL, 61),
(36, '210-10', 'Wesel Bayar', 10, NULL, 61),
(37, '210-15', 'Hutang Giro', 10, NULL, 61),
(38, '210-20', 'Hutang Usaha', 10, NULL, 61),
(39, '210-25', 'Hutang Usaha (USD)', 10, NULL, 61),
(40, '210-30', 'Hutang Konsinyasi', 10, NULL, 61),
(41, '210-40', 'Uang Muka Penjualan', 10, NULL, 61),
(42, '210-55', 'Hutang Deviden', 10, NULL, 61),
(43, '210-60', 'Hutang Bunga', 10, NULL, 61),
(44, '210-65', 'Biaya yang Masih Harus Dibayar', 10, NULL, 61),
(45, '210-75', 'Kartu Kredit', 10, NULL, 61),
(46, '210-80', 'Hutang Pajak Penjualan', 10, NULL, 61),
(47, '210-82', 'Hutang Komisi Penjualan', 10, NULL, 61),
(48, '210-85', 'Hutang Gaji', 10, NULL, 61),
(49, '210-90', 'Hutang Jangka Pendek', 10, NULL, 61),
(50, '220-10', 'Sewa Diterima di Muka', 11, NULL, 61),
(51, '230-10', 'Pinjaman Hipotik', 12, NULL, 61),
(52, '230-20', 'Hutang Bank', 12, NULL, 61),
(53, '310-10', 'Saham Preferen', 13, NULL, 61),
(54, '310-20', 'Modal Disetor', 13, NULL, 61),
(55, '310-30', 'Saham Biasa', 13, NULL, 61),
(56, '320-10', 'Laba ditahan', 14, NULL, 61),
(57, '320-20', 'Laba Tahun Berjalan', 14, NULL, 61),
(58, '320-99', 'Historical Balancing', 14, NULL, 61),
(59, '410-10', 'Penjualan Produk 1', 15, NULL, 61),
(60, '410-20', 'Penjualan Produk 2', 15, NULL, 61),
(61, '410-30', 'Penjualan Produk 3', 15, NULL, 61),
(62, '410-40', 'Penjualan Lain', 15, NULL, 61),
(63, '420-10', 'Potongan Penjualan', 15, NULL, 61),
(64, '410-80', 'Pendapatan Denda Keterlambatan', 20, NULL, 61),
(65, '410-90', 'Pendapatan atas Pengantaran', 20, NULL, 61),
(66, '510-10', 'Biaya 1', 16, NULL, 61),
(67, '510-20', 'Biaya 2', 16, NULL, 61),
(68, '510-30', 'Biaya 3', 16, NULL, 61),
(69, '510-40', 'Komisi Penjualan', 16, NULL, 61),
(70, '510-70', 'Potongan Pembelian', 16, NULL, 61),
(71, '510-80', 'Biaya atas Pengiriman Barang', 16, NULL, 61),
(72, '520-10', 'Kerugian Piutang', 17, NULL, 61),
(73, '520-20', 'Biaya Denda Keterlambatan', 17, NULL, 61),
(74, '520-30', 'Kerusakan dan Kegagalan Material', 17, NULL, 61),
(75, '610-10', 'Gaji Direksi dan Karyawan', 18, NULL, 61),
(76, '610-30', 'Listrik, Air dan Telpon', 18, NULL, 61),
(77, '610-50', 'Promosi dan Iklan', 18, NULL, 61),
(78, '610-60', 'Administrasi Kantor', 18, NULL, 61),
(79, '660-11', 'Penyusutan Mesin dan Peralatan', 19, NULL, 61),
(80, '660-12', 'Penyusutan Mebel dan ATK', 19, NULL, 61),
(81, '660-13', 'Penyusutan Kendaraan', 19, NULL, 61),
(82, '660-15', 'Penyusutan Harta Lainnya', 19, NULL, 61),
(83, '660-16', 'Amortisasi Pra Operasi dan Operasi', 19, NULL, 61),
(84, '810-20', 'Laba Rugi Selisih Kurs', 20, NULL, 61),
(85, '810-30', 'Hasil Sewa', 20, NULL, 61),
(86, '910-10', 'Biaya Bunga', 21, NULL, 61),
(87, '910-11', 'Jasa Bank', 21, NULL, 61),
(88, '240-10', 'Ppn Keluaran', 12, NULL, 61),
(89, '240-20', 'Ppn Masukan', 18, NULL, 61),
(90, '510-90', 'Pembelian', 16, NULL, 61),
(91, '410-9010', 'Pendapatan jasa service', 20, NULL, 61),
(92, '430-10', 'Retur Penjualan', 15, NULL, 61),
(93, '610-70', 'Beban Service dan Perawatan', 18, NULL, 61),
(94, '510-9010', 'Retur Pembelian', 16, NULL, 61),
(95, '410-9030', 'Pendapatan Bunga Bank', 20, NULL, 61),
(96, '660-17', 'Beban administrasi bank', 19, NULL, 61),
(97, '660-18', 'Beban Lain-lain', 19, NULL, 61),
(98, '170-72', 'Perlengkapan Kantor', 7, NULL, 61),
(99, '510-90', 'HPP', 16, NULL, 61),
(100, '660-19', 'Beban Asuransi', 19, NULL, 61),
(101, '660-20', 'Beban Peny.Gedung', 19, NULL, 61),
(102, '660-21', 'Beban Peny.Peralatan', 19, NULL, 61),
(103, '660-22', 'Beban Peny.Kendaraan', 19, NULL, 61),
(104, '610-80', 'Beban Perlengkapan Kantor', 18, NULL, 61);

-- --------------------------------------------------------

--
-- Table structure for table `grup`
--

CREATE TABLE `grup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grup`
--

INSERT INTO `grup` (`id`, `name`) VALUES
(1, 'Harta'),
(2, 'Kewajiban'),
(3, 'Modal'),
(4, 'Pendapatan'),
(5, 'Biaya atas Pendapatan'),
(6, 'Pengeluaran Operasional'),
(7, 'Pengeluaran Non Operasional'),
(8, 'Pendapatan Lain'),
(9, 'Pengeluaran Lain');

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `id` int(11) NOT NULL,
  `tipejurnal_id` int(11) DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  `createon` datetime DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `nomer` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jurnal`
--

INSERT INTO `jurnal` (`id`, `tipejurnal_id`, `period_id`, `createon`, `keterangan`, `person_id`, `nomer`) VALUES
(1, 1, 1, '2011-10-03 14:04:52', 'Penjualan', NULL, NULL),
(2, 1, 1, '2011-10-05 14:36:55', 'Pembayaran hutang bank', NULL, NULL),
(3, 1, 1, '2011-10-05 14:41:57', 'Pembelian kertas HVS 1 Rim', NULL, NULL),
(4, 1, 1, '2011-10-07 14:42:28', 'Pembelian', NULL, NULL),
(5, 1, 1, '2011-10-08 14:53:03', 'Pendapatan jasa service', NULL, NULL),
(6, 1, 1, '2011-10-13 15:01:01', 'Penjualan', NULL, NULL),
(7, 1, 1, '2011-10-15 15:17:20', 'Pembayaran listrik,air dan telp', NULL, NULL),
(8, 1, 1, '2011-10-15 15:18:33', 'Retur penjualan', NULL, NULL),
(9, 1, 1, '2011-10-19 15:26:48', 'Pembelian', NULL, NULL),
(10, 1, 1, '2011-10-19 15:28:58', 'Service AC', NULL, NULL),
(11, 1, 1, '2011-10-20 15:34:26', 'Penjualan tunai', NULL, NULL),
(12, 1, 1, '2011-10-22 15:41:45', 'Retur pembelian', NULL, NULL),
(13, 1, 1, '2011-10-27 16:10:26', 'Pendapatan jasa service', NULL, NULL),
(14, 1, 1, '2011-10-29 16:42:09', 'Pembayaran hutang dagang', NULL, NULL),
(15, 1, 1, '2011-10-30 16:43:21', 'Setoran ke Bank', NULL, NULL),
(16, 1, 1, '2011-10-30 16:44:22', 'Penerimaan piutang', NULL, NULL),
(17, 1, 1, '2011-10-30 16:45:14', 'Pendapatan bunga', NULL, NULL),
(18, 1, 1, '2011-10-30 16:51:42', 'Pembayaran hutang dagang', NULL, NULL),
(19, 1, 1, '2011-10-30 16:52:58', 'Beban adm bank', NULL, NULL),
(20, 1, 1, '2011-10-31 16:57:21', 'Iuran kebersihan dan keamanan', NULL, NULL),
(21, 1, 1, '2011-10-31 17:00:50', 'Pembayaran gaji pegawai', NULL, NULL),
(22, 1, 1, '2011-10-31 17:02:01', 'Perlengkapan kantor', NULL, NULL),
(23, 1, 1, '2011-10-31 17:05:57', 'Persediaan barang dagang', NULL, NULL),
(24, 1, 1, '2011-10-31 17:09:22', 'Asuransi yang telah menjadi beban', NULL, NULL),
(25, 1, 1, '2011-10-31 17:11:32', 'Penyusutan gedung', NULL, NULL),
(26, 1, 1, '2011-10-31 17:16:10', 'Penyusutan kendaraan', NULL, NULL),
(27, 1, 1, '2011-10-31 17:18:42', 'Penyusutan peralatan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jurnald`
--

CREATE TABLE `jurnald` (
  `id` int(11) NOT NULL,
  `jurnal_id` int(11) DEFAULT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `debet` float(15,3) DEFAULT NULL,
  `kredit` float(15,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jurnald`
--

INSERT INTO `jurnald` (`id`, `jurnal_id`, `akun_id`, `debet`, `kredit`) VALUES
(1, 1, 8, 8966100.000, NULL),
(2, 1, 59, NULL, 8580000.000),
(3, 1, 63, 429000.000, NULL),
(4, 1, 88, NULL, 815100.000),
(5, 2, 52, 460000.000, NULL),
(6, 2, 2, NULL, 460000.000),
(7, 3, 98, 40000.000, NULL),
(8, 3, 2, NULL, 40000.000),
(9, 4, 90, 3500000.000, NULL),
(10, 4, 70, NULL, 350000.000),
(11, 4, 89, 315000.000, NULL),
(12, 4, 38, NULL, 3465000.000),
(13, 5, 2, 550000.000, NULL),
(14, 5, 91, NULL, 550000.000),
(15, 6, 8, 13215488.000, NULL),
(16, 6, 59, NULL, 12646400.000),
(17, 6, 63, 632320.000, NULL),
(18, 6, 88, NULL, 1201408.000),
(19, 7, 76, 410000.000, NULL),
(20, 7, 2, NULL, 410000.000),
(21, 8, 92, 520000.000, NULL),
(22, 8, 8, NULL, 543400.000),
(23, 8, 88, 49400.000, NULL),
(24, 8, 63, NULL, 26000.000),
(25, 9, 90, 2280000.000, NULL),
(26, 9, 70, NULL, 228000.000),
(27, 9, 89, 205200.000, NULL),
(28, 9, 38, NULL, 2257200.000),
(29, 10, 93, 25000.000, NULL),
(30, 10, 2, NULL, 25000.000),
(31, 11, 2, 2860000.000, NULL),
(32, 11, 59, NULL, 2600000.000),
(33, 11, 88, NULL, 260000.000),
(34, 12, 38, 118800.000, NULL),
(35, 12, 94, NULL, 120000.000),
(36, 12, 70, 12000.000, NULL),
(37, 12, 89, NULL, 10800.000),
(38, 13, 2, 625000.000, NULL),
(39, 13, 91, NULL, 625000.000),
(40, 14, 38, 1000000.000, NULL),
(41, 14, 4, NULL, 1000000.000),
(42, 15, 4, 1500000.000, NULL),
(43, 15, 2, NULL, 1500000.000),
(44, 16, 4, 3000000.000, NULL),
(45, 16, 8, NULL, 3000000.000),
(46, 17, 4, 72900.000, NULL),
(47, 17, 95, NULL, 72900.000),
(48, 18, 38, 2000000.000, NULL),
(49, 18, 4, NULL, 2000000.000),
(50, 19, 96, 15000.000, NULL),
(51, 19, 4, NULL, 15000.000),
(52, 20, 97, 75000.000, NULL),
(53, 20, 2, NULL, 75000.000),
(54, 21, 75, 2500000.000, NULL),
(55, 21, 4, NULL, 2500000.000),
(56, 22, 104, 140000.000, NULL),
(57, 22, 98, NULL, 140000.000),
(58, 23, 99, 17362000.000, NULL),
(59, 23, 12, NULL, 24186500.000),
(60, 23, 12, 11918500.000, NULL),
(61, 23, 90, NULL, 5780000.000),
(62, 23, 70, 566000.000, NULL),
(63, 23, 94, 120000.000, NULL),
(64, 24, 100, 250000.000, NULL),
(65, 24, 17, NULL, 250000.000),
(66, 25, 101, 600000.000, NULL),
(67, 25, 22, NULL, 600000.000),
(68, 26, 103, 625000.000, NULL),
(69, 26, 28, NULL, 625000.000),
(70, 27, 102, 253000.000, NULL),
(71, 27, 24, NULL, 253000.000);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi`
--

CREATE TABLE `klasifikasi` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `konversi`
--

CREATE TABLE `konversi` (
  `id` int(11) NOT NULL,
  `satuan_id` int(11) DEFAULT NULL,
  `nilai` double(15,3) DEFAULT NULL,
  `satuan_id2` int(11) DEFAULT NULL,
  `operasi` int(11) DEFAULT NULL COMMENT '1 free, 2 kali, 3 bagi',
  `id_FK` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kurs`
--

CREATE TABLE `kurs` (
  `id` int(11) NOT NULL,
  `matauang_id` int(11) DEFAULT NULL,
  `tanggal` int(11) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matauang`
--

CREATE TABLE `matauang` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matauang`
--

INSERT INTO `matauang` (`id`, `kode`, `nama`) VALUES
(1, 'United Arab Emirates dirham', 'AED'),
(2, 'Afghani', 'AFN'),
(3, 'Lek', 'ALL'),
(4, 'Armenian Dram', 'AMD'),
(5, 'Netherlands Antillian Guilder', 'ANG'),
(6, 'Kwanza', 'AOA'),
(7, 'Argentine Peso', 'ARS'),
(8, 'Australian Dollar', 'AUD'),
(9, 'Aruban Guilder', 'AWG'),
(10, 'Azerbaijanian Manat', 'AZN'),
(11, 'Convertible Marks', 'BAM'),
(12, 'Barbados Dollar', 'BBD'),
(13, 'Bangladeshi Taka', 'BDT'),
(14, 'Bulgarian Lev', 'BGN'),
(15, 'Bahraini Dinar', 'BHD'),
(16, 'Burundian Franc', 'BIF'),
(17, 'Bermudian Dollar', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Boliviano', 'BOB'),
(20, 'Brazilian Real', 'BRL'),
(21, 'Bahamian Dollar', 'BSD'),
(22, 'Ngultrum', 'BTN'),
(23, 'Pula', 'BWP'),
(24, 'Belarussian Ruble', 'BYR'),
(25, 'Belize Dollar', 'BZD'),
(26, 'Canadian Dollar', 'CAD'),
(27, 'Franc Congolais', 'CDF'),
(28, 'Swiss Franc', 'CHF'),
(29, 'Chilean Peso', 'CLP'),
(30, 'Yuan Renminbi', 'CNY'),
(31, 'Colombian Peso', 'COP'),
(32, 'Costa Rican Colon', 'CRC'),
(33, 'Cuban Peso', 'CUP'),
(34, 'Cape Verde Escudo', 'CVE'),
(35, 'Cyprus Pound', 'CYP'),
(36, 'Czech Koruna', 'CZK'),
(37, 'Djibouti Franc', 'DJF'),
(38, 'Danish Krone', 'DKK'),
(39, 'Dominican Peso', 'DOP'),
(40, 'Algerian Dinar', 'DZD'),
(41, 'Kroon', 'EEK'),
(42, 'Egyptian Pound', 'EGP'),
(43, 'Nakfa', 'ERN'),
(44, 'Ethiopian Birr', 'ETB'),
(45, 'Euro', 'EUR'),
(46, 'Fiji Dollar', 'FJD'),
(47, 'Falkland Islands Pound', 'FKP'),
(48, 'Pound Sterling', 'GBP'),
(49, 'Lari', 'GEL'),
(50, 'Cedi', 'GHS'),
(51, 'Gibraltar pound', 'GIP'),
(52, 'Dalasi', 'GMD'),
(53, 'Guinea Franc', 'GNF'),
(54, 'Quetzal', 'GTQ'),
(55, 'Guyana Dollar', 'GYD'),
(56, 'Hong Kong Dollar', 'HKD'),
(57, 'Lempira', 'HNL'),
(58, 'Croatian Kuna', 'HRK'),
(59, 'Haiti Gourde', 'HTG'),
(60, 'Forint', 'HUF'),
(61, 'Rupiah', 'IDR'),
(62, 'New Israeli Shekel', 'ILS'),
(63, 'Indian Rupee', 'INR'),
(64, 'Iraqi Dinar', 'IQD'),
(65, 'Iranian Rial', 'IRR'),
(66, 'Iceland Krona', 'ISK'),
(67, 'Jamaican Dollar', 'JMD'),
(68, 'Jordanian Dinar', 'JOD'),
(69, 'Japanese yen', 'JPY'),
(70, 'Kenyan Shilling', 'KES'),
(71, 'Som', 'KGS'),
(72, 'Riel', 'KHR'),
(73, 'Comoro Franc', 'KMF'),
(74, 'North Korean Won', 'KPW'),
(75, 'South Korean Won', 'KRW'),
(76, 'Kuwaiti Dinar', 'KWD'),
(77, 'Cayman Islands Dollar', 'KYD'),
(78, 'Tenge', 'KZT'),
(79, 'Kip', 'LAK'),
(80, 'Lebanese Pound', 'LBP'),
(81, 'Sri Lanka Rupee', 'LKR'),
(82, 'Liberian Dollar', 'LRD'),
(83, 'Loti', 'LSL'),
(84, 'Lithuanian Litas', 'LTL'),
(85, 'Latvian Lats', 'LVL'),
(86, 'Libyan Dinar', 'LYD'),
(87, 'Moroccan Dirham', 'MAD'),
(88, 'Moldovan Leu', 'MDL'),
(89, 'Malagasy Ariary', 'MGA'),
(90, 'Denar', 'MKD'),
(91, 'Kyat', 'MMK'),
(92, 'Tugrik', 'MNT'),
(93, 'Pataca', 'MOP'),
(94, 'Ouguiya', 'MRO'),
(95, 'Maltese Lira', 'MTL'),
(96, 'Mauritius Rupee', 'MUR'),
(97, 'Rufiyaa', 'MVR'),
(98, 'Kwacha', 'MWK'),
(99, 'Mexican Peso', 'MXN'),
(100, 'Malaysian Ringgit', 'MYR'),
(101, 'Metical', 'MZN'),
(102, 'Namibian Dollar', 'NAD'),
(103, 'Naira', 'NGN'),
(104, 'Cordoba Oro', 'NIO'),
(105, 'Norwegian Krone', 'NOK'),
(106, 'Nepalese Rupee', 'NPR'),
(107, 'New Zealand Dollar', 'NZD'),
(108, 'Rial Omani', 'OMR'),
(109, 'Balboa', 'PAB'),
(110, 'Nuevo Sol', 'PEN'),
(111, 'Kina', 'PGK'),
(112, 'Philippine Peso', 'PHP'),
(113, 'Pakistan Rupee', 'PKR'),
(114, 'Zloty', 'PLN'),
(115, 'Guarani', 'PYG'),
(116, 'Qatari Rial', 'QAR'),
(117, 'Romanian New Leu', 'RON'),
(118, 'Serbian Dinar', 'RSD'),
(119, 'Russian Ruble', 'RUB'),
(120, 'Rwanda Franc', 'RWF'),
(121, 'Saudi Riyal', 'SAR'),
(122, 'Solomon Islands Dollar', 'SBD'),
(123, 'Seychelles Rupee', 'SCR'),
(124, 'Sudanese Pound', 'SDG'),
(125, 'Swedish Krona', 'SEK'),
(126, 'Singapore Dollar', 'SGD'),
(127, 'Saint Helena Pound', 'SHP'),
(128, 'Slovak Koruna', 'SKK'),
(129, 'Leone', 'SLL'),
(130, 'Somali Shilling', 'SOS'),
(131, 'Surinam Dollar', 'SRD'),
(132, 'Dobra', 'STD'),
(133, 'Syrian Pound', 'SYP'),
(134, 'Lilangeni', 'SZL'),
(135, 'Baht', 'THB'),
(136, 'Somoni', 'TJS'),
(137, 'Manat', 'TMM'),
(138, 'Tunisian Dinar', 'TND'),
(139, 'Pa\'anga', 'TOP'),
(140, 'New Turkish Lira', 'TRY'),
(141, 'Trinidad and Tobago Dollar', 'TTD'),
(142, 'New Taiwan Dollar', 'TWD'),
(143, 'Tanzanian Shilling', 'TZS'),
(144, 'Hryvnia', 'UAH'),
(145, 'Uganda Shilling', 'UGX'),
(146, 'US Dollar', 'USD'),
(147, 'Peso Uruguayo', 'UYU'),
(148, 'Uzbekistan Som', 'UZS'),
(149, 'Venezuelan bolívar', 'VEB'),
(150, 'Vietnamese dong', 'VND'),
(151, 'Vatu', 'VUV'),
(152, 'Samoan Tala', 'WST'),
(153, 'CFA Franc BEAC', 'XAF'),
(154, 'Silver (one Troy ounce)', 'XAG'),
(155, 'Gold (one Troy ounce)', 'XAU'),
(156, 'CFA Franc BCEAO', 'XOF'),
(157, 'Palladium (one Troy ounce)', 'XPD'),
(158, 'CFP franc', 'XPF'),
(159, 'Platinum (one Troy ounce)', 'XPT'),
(160, 'Yemeni Rial', 'YER'),
(161, 'South African Rand', 'ZAR'),
(162, 'Kwacha', 'ZMK'),
(163, 'Zimbabwe Dollar', 'ZWD');

-- --------------------------------------------------------

--
-- Table structure for table `pajak`
--

CREATE TABLE `pajak` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `nilai` double(15,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `akunjual` int(11) DEFAULT NULL,
  `akunbeli` int(11) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `tipe` int(11) DEFAULT NULL COMMENT '1 volume, 2 weight, 3value'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id` int(11) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `isaktif` bit(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`id`, `start`, `end`, `isaktif`, `user_id`) VALUES
(1, '2011-10-01 10:08:21', '2011-10-31 10:08:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `telp1` varchar(50) DEFAULT NULL,
  `matauang_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `telp2` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `hp` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `zip` varchar(50) DEFAULT NULL,
  `klasifikasi_id` int(11) DEFAULT NULL,
  `id_FK` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `kode`, `nama`, `kontak`, `type_id`, `telp1`, `matauang_id`, `username`, `password`, `telp2`, `fax`, `hp`, `email`, `website`, `npwp`, `alamat`, `kota`, `zip`, `klasifikasi_id`, `id_FK`) VALUES
(1, 'V', 'PT ANDALAS', NULL, 1, NULL, NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 'K1', 'Administrator', NULL, 3, NULL, NULL, 'admin', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `kelompok_id` int(11) DEFAULT NULL,
  `satuan_id` int(11) DEFAULT NULL,
  `satuan_id2` int(11) DEFAULT NULL COMMENT 'onreport',
  `gudang_id` int(11) DEFAULT NULL,
  `minstok` double(15,3) DEFAULT NULL,
  `minorder` double(15,3) DEFAULT NULL,
  `akunhpp` int(11) DEFAULT NULL,
  `akunjual` int(11) DEFAULT NULL,
  `akunpersediaan` int(11) DEFAULT NULL,
  `akunreturjual` int(11) DEFAULT NULL,
  `hargapokok` double(15,3) DEFAULT NULL COMMENT '1 fifo, 2 lifo, 3 ratarata',
  `p` double(15,3) DEFAULT NULL,
  `l` double(15,3) DEFAULT NULL,
  `t` double(15,3) DEFAULT NULL,
  `berat` double(15,3) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `waktukirim` int(11) DEFAULT NULL,
  `aktif` int(11) DEFAULT NULL COMMENT '1 ya',
  `id_FK` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saldoawal`
--

CREATE TABLE `saldoawal` (
  `id` int(11) NOT NULL,
  `periode_id` int(11) DEFAULT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `debet` float(9,3) DEFAULT NULL,
  `kredit` tinytext,
  `user_id` int(11) DEFAULT NULL,
  `saldo` double(15,3) DEFAULT '0.000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `saldoawal`
--

INSERT INTO `saldoawal` (`id`, `periode_id`, `akun_id`, `debet`, `kredit`, `user_id`, `saldo`) VALUES
(7, 1, 2, NULL, NULL, NULL, 2600000.000),
(8, 1, 4, NULL, NULL, NULL, 26725000.000),
(9, 1, 8, NULL, NULL, NULL, 17123000.000),
(10, 1, 12, NULL, NULL, NULL, 24186500.000),
(11, 1, 23, NULL, NULL, NULL, 7650000.000),
(12, 1, 17, NULL, NULL, NULL, 2400000.000),
(13, 1, 21, NULL, NULL, NULL, 150000000.000),
(14, 1, 22, NULL, NULL, NULL, -4800000.000),
(15, 1, 27, NULL, NULL, NULL, 85000000.000),
(16, 1, 28, NULL, NULL, NULL, -4375000.000),
(17, 1, 98, NULL, NULL, NULL, 900000.000),
(18, 1, 24, NULL, NULL, NULL, -1771000.000),
(19, 1, 38, NULL, NULL, NULL, 14625000.000),
(20, 1, 52, NULL, NULL, NULL, 47700000.000),
(21, 1, 54, NULL, NULL, NULL, 243313500.000);

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `unitdasar` int(11) DEFAULT NULL COMMENT '1 ya'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subgrup`
--

CREATE TABLE `subgrup` (
  `id` int(11) NOT NULL,
  `grup_id` int(11) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subgrup`
--

INSERT INTO `subgrup` (`id`, `grup_id`, `kode`, `nama`) VALUES
(1, 1, '110', 'Kas'),
(2, 1, '120', 'Bank'),
(3, 1, '130', 'Piutang Dagang'),
(4, 1, '140', 'Persediaan'),
(5, 1, '150', 'Biaya Dibayar Dimuka'),
(6, 1, '160', 'Investasi Jangka Panjang'),
(7, 1, '170', 'Harta Tetap Berwujud'),
(8, 1, '180', 'Harta Tetap Tidak Berwujud'),
(9, 1, '190', 'Harta Lainnya'),
(10, 2, '210', 'Hutang Lancar'),
(11, 2, '220', 'Pendapatan yang diterima di muka'),
(12, 2, '230', 'Hutang Jangka Panjang'),
(13, 3, '310', 'Modal'),
(14, 3, '320', 'Laba'),
(15, 4, '410', 'Pendapatan Usaha'),
(16, 5, '510', 'Biaya Produksi'),
(17, 5, '520', 'Biaya Lain'),
(18, 6, '610', 'Biaya Operasional'),
(19, 7, '660', 'Biaya Non Operasional'),
(20, 8, '810', 'Pendapatan Luar Usaha'),
(21, 9, '910', 'Pengeluaran Luar Usaha');

-- --------------------------------------------------------

--
-- Table structure for table `tipejurnal`
--

CREATE TABLE `tipejurnal` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipejurnal`
--

INSERT INTO `tipejurnal` (`id`, `kode`, `nama`) VALUES
(1, 'JU', 'Jurnal Umum'),
(2, 'KM', 'Kas Masuk'),
(3, 'KK', 'Kas Keluar');

-- --------------------------------------------------------

--
-- Table structure for table `top`
--

CREATE TABLE `top` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='term of payment';

-- --------------------------------------------------------

--
-- Table structure for table `tos`
--

CREATE TABLE `tos` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='term of sales';

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `kode`, `nama`) VALUES
(1, 'V', 'Supplier'),
(2, 'P', 'Pelanggan'),
(3, 'K', 'Karyawan'),
(4, 'L', 'Lainya');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_MatauangAkun` (`matauang_id`),
  ADD KEY `fk_SubgrupAkun` (`subgrup_id`);

--
-- Indexes for table `grup`
--
ALTER TABLE `grup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PeriodeJurnal` (`period_id`),
  ADD KEY `fk_TipejurnalJurnal` (`tipejurnal_id`);

--
-- Indexes for table `jurnald`
--
ALTER TABLE `jurnald`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_JurnalJurnald` (`jurnal_id`),
  ADD KEY `fk_AkunJurnald` (`akun_id`);

--
-- Indexes for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klasifikasi`
--
ALTER TABLE `klasifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konversi`
--
ALTER TABLE `konversi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_SatuanKonversi` (`satuan_id`),
  ADD KEY `fk_SatuanKonversi2` (`satuan_id2`);

--
-- Indexes for table `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matauang`
--
ALTER TABLE `matauang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pajak`
--
ALTER TABLE `pajak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_KlasifikasiPerson` (`klasifikasi_id`),
  ADD KEY `fk_MatauangPerson` (`matauang_id`),
  ADD KEY `fk_TypePerson` (`type_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_SatuanProduk` (`satuan_id`),
  ADD KEY `fk_SatuanProduk2` (`satuan_id2`),
  ADD KEY `fk_SupplierProduk` (`supplier_id`),
  ADD KEY `fk_GudangProduk` (`gudang_id`),
  ADD KEY `fk_KelompokProduk` (`kelompok_id`);

--
-- Indexes for table `saldoawal`
--
ALTER TABLE `saldoawal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PeriodeSaldoawal` (`periode_id`),
  ADD KEY `fk_AkunSaldoawal` (`akun_id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subgrup`
--
ALTER TABLE `subgrup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_GrupSubgrup` (`grup_id`);

--
-- Indexes for table `tipejurnal`
--
ALTER TABLE `tipejurnal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top`
--
ALTER TABLE `top`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tos`
--
ALTER TABLE `tos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `grup`
--
ALTER TABLE `grup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jurnald`
--
ALTER TABLE `jurnald`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konversi`
--
ALTER TABLE `konversi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurs`
--
ALTER TABLE `kurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matauang`
--
ALTER TABLE `matauang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `pajak`
--
ALTER TABLE `pajak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode`
--
ALTER TABLE `periode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saldoawal`
--
ALTER TABLE `saldoawal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subgrup`
--
ALTER TABLE `subgrup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tipejurnal`
--
ALTER TABLE `tipejurnal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `top`
--
ALTER TABLE `top`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tos`
--
ALTER TABLE `tos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun`
--
ALTER TABLE `akun`
  ADD CONSTRAINT `fk_MatauangAkun` FOREIGN KEY (`matauang_id`) REFERENCES `matauang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SubgrupAkun` FOREIGN KEY (`subgrup_id`) REFERENCES `subgrup` (`id`);

--
-- Constraints for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD CONSTRAINT `fk_PeriodeJurnal` FOREIGN KEY (`period_id`) REFERENCES `periode` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TipejurnalJurnal` FOREIGN KEY (`tipejurnal_id`) REFERENCES `tipejurnal` (`id`);

--
-- Constraints for table `jurnald`
--
ALTER TABLE `jurnald`
  ADD CONSTRAINT `fk_AkunJurnald` FOREIGN KEY (`akun_id`) REFERENCES `akun` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_JurnalJurnald` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `konversi`
--
ALTER TABLE `konversi`
  ADD CONSTRAINT `fk_SatuanKonversi` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SatuanKonversi2` FOREIGN KEY (`satuan_id2`) REFERENCES `satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `fk_KlasifikasiPerson` FOREIGN KEY (`klasifikasi_id`) REFERENCES `klasifikasi` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_MatauangPerson` FOREIGN KEY (`matauang_id`) REFERENCES `matauang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TypePerson` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_GudangProduk` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_KelompokProduk` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SatuanProduk` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SatuanProduk2` FOREIGN KEY (`satuan_id2`) REFERENCES `satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SupplierProduk` FOREIGN KEY (`supplier_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `saldoawal`
--
ALTER TABLE `saldoawal`
  ADD CONSTRAINT `fk_AkunSaldoawal` FOREIGN KEY (`akun_id`) REFERENCES `akun` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PeriodeSaldoawal` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subgrup`
--
ALTER TABLE `subgrup`
  ADD CONSTRAINT `fk_GrupSubgrup` FOREIGN KEY (`grup_id`) REFERENCES `grup` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
