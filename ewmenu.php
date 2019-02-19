<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, "mci_Menu2d1", $Language->MenuPhrase("23", "MenuText"), "", -1, "", TRUE, TRUE, TRUE);
$RootMenu->AddMenuItem(2, "mi_grup", $Language->MenuPhrase("2", "MenuText"), "gruplist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mi_matauang", $Language->MenuPhrase("10", "MenuText"), "matauanglist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_akun", $Language->MenuPhrase("1", "MenuText"), "akunlist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mi_saldoawal", $Language->MenuPhrase("16", "MenuText"), "saldoawallist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_gudang", $Language->MenuPhrase("3", "MenuText"), "gudanglist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_jurnal", $Language->MenuPhrase("4", "MenuText"), "jurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_jurnald", $Language->MenuPhrase("5", "MenuText"), "jurnaldlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_kelompok", $Language->MenuPhrase("6", "MenuText"), "kelompoklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_klasifikasi", $Language->MenuPhrase("7", "MenuText"), "klasifikasilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mi_konversi", $Language->MenuPhrase("8", "MenuText"), "konversilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mi_kurs", $Language->MenuPhrase("9", "MenuText"), "kurslist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_pajak", $Language->MenuPhrase("11", "MenuText"), "pajaklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mi_pengiriman", $Language->MenuPhrase("12", "MenuText"), "pengirimanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mi_periode", $Language->MenuPhrase("13", "MenuText"), "periodelist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mi_person", $Language->MenuPhrase("14", "MenuText"), "personlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mi_produk", $Language->MenuPhrase("15", "MenuText"), "produklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mi_satuan", $Language->MenuPhrase("17", "MenuText"), "satuanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mi_tipejurnal", $Language->MenuPhrase("19", "MenuText"), "tipejurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mi_top", $Language->MenuPhrase("20", "MenuText"), "toplist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(21, "mi_tos", $Language->MenuPhrase("21", "MenuText"), "toslist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(22, "mi_type", $Language->MenuPhrase("22", "MenuText"), "typelist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
