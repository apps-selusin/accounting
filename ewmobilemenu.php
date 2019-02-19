<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, "mmci_Menu2d1", $Language->MenuPhrase("23", "MenuText"), "", -1, "", TRUE, TRUE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_grup", $Language->MenuPhrase("2", "MenuText"), "gruplist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_matauang", $Language->MenuPhrase("10", "MenuText"), "matauanglist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_akun", $Language->MenuPhrase("1", "MenuText"), "akunlist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mmi_saldoawal", $Language->MenuPhrase("16", "MenuText"), "saldoawallist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_gudang", $Language->MenuPhrase("3", "MenuText"), "gudanglist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_jurnal", $Language->MenuPhrase("4", "MenuText"), "jurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_jurnald", $Language->MenuPhrase("5", "MenuText"), "jurnaldlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_kelompok", $Language->MenuPhrase("6", "MenuText"), "kelompoklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mmi_klasifikasi", $Language->MenuPhrase("7", "MenuText"), "klasifikasilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mmi_konversi", $Language->MenuPhrase("8", "MenuText"), "konversilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mmi_kurs", $Language->MenuPhrase("9", "MenuText"), "kurslist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mmi_pajak", $Language->MenuPhrase("11", "MenuText"), "pajaklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mmi_pengiriman", $Language->MenuPhrase("12", "MenuText"), "pengirimanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mmi_periode", $Language->MenuPhrase("13", "MenuText"), "periodelist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mmi_person", $Language->MenuPhrase("14", "MenuText"), "personlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mmi_produk", $Language->MenuPhrase("15", "MenuText"), "produklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mmi_satuan", $Language->MenuPhrase("17", "MenuText"), "satuanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mmi_tipejurnal", $Language->MenuPhrase("19", "MenuText"), "tipejurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mmi_top", $Language->MenuPhrase("20", "MenuText"), "toplist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(21, "mmi_tos", $Language->MenuPhrase("21", "MenuText"), "toslist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(22, "mmi_type", $Language->MenuPhrase("22", "MenuText"), "typelist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
