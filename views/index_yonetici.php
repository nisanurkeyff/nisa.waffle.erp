<?
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');
	session_kontrol();
    
    $row_sayi           = $cUrun->getUrunSayisi();
    $rows_sube_giris    = $cSube->getMenuGirisSayilari($_REQUEST);

    $arr_sube_giris = new stdClass();
    foreach($rows_sube_giris as $key => $row_sube_giris){
        $arr_sube_giris->sube[]       = $row_sube_giris->SUBE;
        $arr_sube_giris->sayi[]       = $row_sube_giris->SAY;
    }

?>
<!Doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template" data-style="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title> <?=$row_site->TITLE?> | Dashboard </title>
        <?=$cTheme->Linkler()?>
    </head>
    <style type="text/css">
        .custom-link {
            color: black !important;
            text-decoration: none;
        }

        .custom-link:hover {
            color: black !important;
            text-decoration: none;
        }
    </style>
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <?=$cTheme->Menu()?>
                <div class="layout-page">
                    <?=$cTheme->Header()?>
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row g-6">

                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <a href="/views/urun/urun_listesi.php?durum=1" class="custom-link">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <div class="avatar me-4">
                                                        <div class="avatar-initial bg-label-success rounded-3">
                                                            <i class="ri-check-double-line ri-24px">
                                                            </i>
                                                        </div>
                                                    </div>
                                                    <div class="card-info">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="mb-0 me-2"><?=FormatSayi::sayi($row_sayi->AKTIF,0)?></h5>
                                                        </div>
                                                        <p class="mb-0">Aktif Ürünler</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <a href="/views/urun/urun_listesi.php?durum=0" class="custom-link">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <div class="avatar me-4">
                                                        <div class="avatar-initial bg-label-warning rounded-3">
                                                            <i class="ri-loader-line ri-24px">
                                                            </i>
                                                        </div>
                                                    </div>
                                                    <div class="card-info">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="mb-0 me-2"><?=FormatSayi::sayi($row_sayi->PASIF,0)?></h5>
                                                        </div>
                                                        <p class="mb-0">Pasif Ürünler</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-xl-12 col-12 mb-6">
                                    <div class="card">
                                        <div class="card-header header-elements">
                                            <h5 class="card-title mb-0">QR Menü Giriş Sayıları</h5>
                                            <div class="card-action-element ms-auto py-0">
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-calendar-2-line"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 0)" class="dropdown-item d-flex align-items-center">Bugün</a></li>
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 1)" class="dropdown-item d-flex align-items-center">Dün</a></li>
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 7)" class="dropdown-item d-flex align-items-center">7 Gün Önce</a></li>
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 30)" class="dropdown-item d-flex align-items-center">30 Gün Önce</a></li>
                                                        <li><hr class="dropdown-divider" /></li>
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 'buAy')" class="dropdown-item d-flex align-items-center">Bu Ay</a></li>
                                                        <li><a href="javascript:void(0);" onclick="fncTarihSec(this, 'gecenAy')" class="dropdown-item d-flex align-items-center">Geçen Ay</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="barChart" class="chartjs" data-height="400" style="height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?=$cTheme->Footer()?>
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
            <div class="drag-target"></div>
        </div>
        <?=$cTheme->Scriptler()?>
    </body>
</html>
<script type="text/javascript">

    function fncProfil(obj){
        $.ajax({
            url: "/router.php",
            type: "POST",
            data: {id: $(obj).data("id"), controller: "kullanici", action: "kullanici_duzenle"},
            dataType: "json",
            success: function(response) {
                if (response.HATA) {
                    Swal.fire({title: 'Uyarı!',text: response.ACIKLAMA ,icon: 'warning' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                } else {
                    location.href = response.URL;
                }
            }
        });
    }

    var url = new URLSearchParams(window.location.search);
    function fncUrlGuncelle(url) {
        var urlYeni = new URL(window.location.href);
        url.forEach(function(value, key) {
            urlYeni.searchParams.set(key, value);
        });
        window.location.href = urlYeni.href;
    }

    function fncTarihSec(obj, gun) {
        let bugun = new Date();
        let baslangic = new Date();
        
        if (gun === "buAy") {
            baslangic.setDate(1); // Ayın ilk günü
        } else if (gun === "gecenAy") {
            baslangic.setMonth(baslangic.getMonth() - 1);
            baslangic.setDate(1); // Geçen ayın ilk günü
            bugun = new Date(baslangic.getFullYear(), baslangic.getMonth() + 1, 0); // Geçen ayın son günü
        } else {
            baslangic.setDate(baslangic.getDate() - gun);
        }

        // Türkiye saatine göre YYYY-MM-DD formatına çevir
        let formatliBaslangic = baslangic.toLocaleDateString('tr-TR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }).split('.').reverse().join('-'); 

        let formatliBitis = bugun.toLocaleDateString('tr-TR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }).split('.').reverse().join('-'); 

        var url = new URLSearchParams(window.location.search);
        url.set('tarih', formatliBitis + ',' + formatliBaslangic);
        
        fncUrlGuncelle(url);
    }

    fncBarChart(<?=json_encode($arr_sube_giris->sube)?>, <?=json_encode($arr_sube_giris->sayi)?>);

</script>