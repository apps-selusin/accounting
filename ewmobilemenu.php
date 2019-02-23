<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(24, "mmi_cf01_home_php", $Language->MenuPhrase("24", "MenuText"), "cf01_home.php", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(23, "mmci_Setup", $Language->MenuPhrase("23", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(13, "mmi_periode", $Language->MenuPhrase("13", "MenuText"), "periodelist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_matauang", $Language->MenuPhrase("10", "MenuText"), "matauanglist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(48, "mmci_Akun", $Language->MenuPhrase("48", "MenuText"), "", 23, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_grup", $Language->MenuPhrase("2", "MenuText"), "gruplist.php", 48, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(18, "mmi_subgrup", $Language->MenuPhrase("18", "MenuText"), "subgruplist.php", 48, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_akun", $Language->MenuPhrase("1", "MenuText"), "akunlist.php", 48, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mmi_saldoawal", $Language->MenuPhrase("16", "MenuText"), "saldoawallist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mmi_tipejurnal", $Language->MenuPhrase("19", "MenuText"), "tipejurnallist.php", 23, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(77, "mmci_Transaksi", $Language->MenuPhrase("77", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(4, "mmi_jurnal", $Language->MenuPhrase("4", "MenuText"), "jurnallist.php", 77, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(78, "mmci_Laporan", $Language->MenuPhrase("78", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(49, "mmi_cf02_labarugi_php", $Language->MenuPhrase("49", "MenuText"), "cf02_labarugi.php", 78, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(50, "mmi_cf03_neraca_php", $Language->MenuPhrase("50", "MenuText"), "cf03_neraca.php", 78, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(79, "mmi_cf04_jurnal_php", $Language->MenuPhrase("79", "MenuText"), "cf04_jurnal.php", 78, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(80, "mmi_cf05_coa_php", $Language->MenuPhrase("80", "MenuText"), "cf05_coa.php", 78, "", TRUE, FALSE, TRUE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
