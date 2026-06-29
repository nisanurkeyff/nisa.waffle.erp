<?
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');
    session_kontrol();

    $excel = new excelSayfasi();
    $excel->sutunEkle("Üst Kategori","UST_KATEGORI","");
    $excel->sutunEkle("Kategori","KATEGORI","");
    $excel->sutunEkle("Kategori İngilizce","KATEGORI_ENG","");
    $excel->sutunEkle("Sıra","SIRA","");
    $excel->sutunEkle("Durum","DURUM","");
    $excelOut = $excel->excel();


    $rows_ust_kategoriler   = $cKategori->getUstKategoriler($_REQUEST);
    $rows_kategoriler       = $cKategori->getKategoriler($_REQUEST);

    foreach ($rows_kategoriler as $key => $row_kategoriler2) {
        $rows_kategori_grup[$row_kategoriler2->UST_KATEGORI][] = $row_kategoriler2;
    }

    $_SESSION["Table"]  = $result;
    $_SESSION['excel']  = $excelOut;
?>
<!Doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template" data-style="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title> <?=$row_site->TITLE?> | Kategori </title>
        <?=$cTheme->Linkler()?>
    </head>
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <?=$cTheme->Menu()?>
                <div class="layout-page">
                    <?=$cTheme->Header()?>
                    <div class="content-wrapper">

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-8">
                                        <div class="card mb-6">
                                            <div class="card-header header-elements bg-primary py-1">
                                                <h6 class="mb-0 me-2 text-white"> <i class="ri-folders-line fs-4 me-2"></i> Üst Kategoriler</h6>
                                                <div class="card-header-elements ms-auto">
                                                    <a href="javascript:;" class="btn btn-icon text-white float-right border-white borderd-radius btn-sm" data-bs-target="#ustkategoriEkleModal" data-bs-toggle="modal"><i class="ri-add-line fs-4"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body mt-2">
                                                <div class="card-datatable text-nowrap table-responsive">
                                                    <table class="table table-hover table-sm">
                                                        <thead class="thead-themed fw-bold py-0">
                                                            <tr class="table-primary">
                                                                <td>#</td>
                                                                <td>Üst Kategori</td>
                                                                <td>Üst Kategori İngilizce</td>
                                                                <td>Sıra</td>
                                                                <td>Durum</td>
                                                                <td></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?foreach ($rows_ust_kategoriler as $key => $row_ust_kategoriler) {?>
                                                                <tr>
                                                                    <td><?=($key+1)?></td>
                                                                    <td><?=$row_ust_kategoriler->UST_KATEGORI?></td>
                                                                    <td><?=$row_ust_kategoriler->UST_KATEGORI_ENG?></td>
                                                                    <td><?=$row_ust_kategoriler->SIRA?></td>
                                                                    <td><?=fncDurumSpan($row_ust_kategoriler->DURUM)?></td>
                                                                    <td align="right">
                                                                        <a href="javascript:;" class="btn btn-primary btn-icon btn-sm" data-id="<?=$row_ust_kategoriler->ID?>" onclick="fncUstKategoriBilgisi(this)" data-bs-target="#ustkategoriDuzenleModal" data-bs-toggle="modal"><i class="ri-pencil-line"></i></a>
                                                                        <a href="javascript:;" class="btn btn-danger btn-icon btn-sm" data-id="<?=$row_ust_kategoriler->ID?>" onclick="fncUstKategoriSil(this)"><i class="ri-delete-bin-5-line"></i></a>
                                                                    </td>
                                                                </tr>
                                                            <?}?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card mb-6">
                                            <div class="card-header header-elements bg-primary py-1">
                                                <h6 class="mb-0 me-2 text-white"> <i class="ri-folders-line fs-4 me-2"></i> Kategoriler</h6>
                                                <div class="card-header-elements ms-auto">
                                                    <a href="javascript:;" class="btn btn-icon text-white float-right border-white borderd-radius btn-sm" data-bs-target="#kategoriEkleModal" data-bs-toggle="modal"><i class="ri-add-line fs-4"></i></a>
                                                    <a href="../excel_sql.php" data-bs-toggle="tooltip" title="Excel" class="btn btn-icon text-white float-right border-white borderd-radius btn-sm"> <i class="ri-file-excel-2-line"></i> </a>
                                                </div>
                                            </div>
                                            <div class="card-body mt-2">
                                                <div class="card-datatable text-nowrap table-responsive">
                                                    <table class="table table-hover table-sm">
                                                        <thead class="thead-themed fw-bold py-0">
                                                            <tr class="table-primary">
                                                                <td>#</td>
                                                                <td>Üst Kategori</td>
                                                                <td>Kategori</td>
                                                                <td>Kategori İngilizce</td>
                                                                <td>Sıra</td>
                                                                <td>Durum</td>
                                                                <td></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?foreach ($rows_kategori_grup as $ust_kategori => $rows_kategori) {
                                                                $ust_class = preg_replace('/[^a-zA-Z0-9]/', '_', $ust_kategori);
                                                                ?>
                                                                <tr class="table-dark">
                                                                    <td colspan="7" class="text-center position-relative">
                                                                        <?=$ust_kategori?>
                                                                        <a href="javascript:void(0)" class="btn btn-dark btn-sm position-absolute end-0 top-50 translate-middle-y" onclick="fncGizle(this)" data-ust_kategori="<?=$ust_class?>">
                                                                        <i class="ri-arrow-down-double-line"></i></a>
                                                                    </td>
                                                                </tr>
                                                                <tbody class="sortable ust_kategori<?=$ust_class?>">
                                                                    <?foreach ($rows_kategori as $key => $row) { ?>
                                                                        <tr data-id="<?=$row->ID?>">
                                                                            <td><?=($key + 1)?></td>
                                                                            <td><?=$row->UST_KATEGORI?></td>
                                                                            <td><?=$row->KATEGORI?></td>
                                                                            <td><?=$row->KATEGORI_ENG?></td>
                                                                            <td><?=$row->SIRA?></td>
                                                                            <td><?=fncDurumSpan($row->DURUM)?></td>
                                                                            <td class="text-end">
                                                                                <a href="javascript:;" class="btn btn-primary btn-sm btn-icon" data-id="<?=$row->ID?>" onclick="fncKategoriBilgisi(this)" data-bs-toggle="modal" data-bs-target="#kategoriDuzenleModal"><i class="ri-pencil-line"></i></a>
                                                                                <a href="javascript:;" class="btn btn-danger btn-sm btn-icon" data-id="<?=$row->ID?>" onclick="fncKategoriSil(this)"><i class="ri-delete-bin-5-line"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?}?>
                                                                </tbody>
                                                            <?}?>
                                                        </tbody>  
                                                    </table>
                                                </div>
                                            </div>
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

<div class="modal fade" id="ustkategoriEkleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="mb-2">Üst Kategori Ekle</h4>
                </div>
                <form id="ustkategoriEkle" class="row g-5">
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="ust_kategori" name="ust_kategori" class="form-control" maxlength="45"/>
                            <label>Üst Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="ust_kategori_eng" name="ust_kategori_eng" class="form-control" maxlength="45"/>
                            <label>Üst Kategori İngilizce</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="durum" id="durum" class="select2 form-select" data-style="btn-default">
                                <?=$cKullanici->Durum()->setSecilen(1)->getSelect("ID", "AD")?>
                            </select>
                            <label>Durum</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="sira" name="sira" class="form-control" maxlength="3"/>
                            <label>Sıra</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <input type="file" class="form-control" name="resim" id="resim">
                    </div>
                    <div class="col-12 text-right d-flex flex-wrap justify-content-end gap-4 row-gap-4">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Kapat">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ustkategoriDuzenleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="mb-2">Üst Kategori Düzenle</h4>
                </div>
                <form id="ustkategoriDuzenle" class="row g-5">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="col-md-12 col-12 mb-4 text-center">
                        <img id="mevcutResim" class="rounded-3 fancybox" src="/img/logo.png" alt="Mevcut Resim" height="150">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="ust_kategori" name="ust_kategori" class="form-control" maxlength="45"/>
                            <label>Üst Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="ust_kategori_eng" name="ust_kategori_eng" class="form-control" maxlength="45"/>
                            <label>Üst Kategori İngilizce</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="durum" id="durum2" class="select2 form-select" data-style="btn-default">
                                <?=$cKullanici->Durum()->setSecilen()->getSelect("ID", "AD")?>
                            </select>
                            <label>Durum</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="sira" name="sira" class="form-control" maxlength="3"/>
                            <label>Sıra</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <input type="file" class="form-control" name="resim" id="resim">
                    </div>
                    <div class="col-12 text-right d-flex flex-wrap justify-content-end gap-4 row-gap-4">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-danger" onclick="fncUstKategoriResimSil(this)">Resmi Sil</button>
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Kapat">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kategoriEkleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="mb-2">Kategori Ekle</h4>
                </div>
                <form id="kategoriEkle" class="row g-5">
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="ust_kategori_id" id="ust_kategori_id" class="select2 form-select" data-style="btn-default">
                                <?=$cKategori->UstKategoriler()->setSecilen()->setSeciniz()->getSelect("ID", "AD")?>
                            </select>
                            <label>Üst Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="kategori" name="kategori" class="form-control" maxlength="45"/>
                            <label>Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="kategori_eng" name="kategori_eng" class="form-control" maxlength="45"/>
                            <label>Kategori İngilizce</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="durum" id="durum3" class="select2 form-select" data-style="btn-default">
                                <?=$cKullanici->Durum()->setSecilen(1)->getSelect("ID", "AD")?>
                            </select>
                            <label>Durum</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <input type="file" class="form-control" name="resim" id="resim">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="sira" name="sira" class="form-control" maxlength="3"/>
                            <label>Sıra</label>
                        </div>
                    </div>
                    <div class="col-12 text-right d-flex flex-wrap justify-content-end gap-4 row-gap-4">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Kapat">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kategoriDuzenleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="mb-2">Kategori Düzenle</h4>
                </div>
                <form id="kategoriDuzenle" class="row g-5">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="col-md-12 col-12 mb-4 text-center">
                        <img id="mevcutResim" class="rounded-3 fancybox" src="/img/logo.png" alt="Mevcut Resim" height="150">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="ust_kategori_id" id="ust_kategori_id2" class="select2 form-select" data-style="btn-default">
                                <?=$cKategori->UstKategoriler()->setSecilen()->setSeciniz()->getSelect("ID", "AD")?>
                            </select>
                            <label>Üst Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="kategori" name="kategori" class="form-control" maxlength="45"/>
                            <label>Kategori</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="kategori_eng" name="kategori_eng" class="form-control" maxlength="45"/>
                            <label>Kategori İngilizce</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="durum" id="durum4" class="select2 form-select" data-style="btn-default">
                                <?=$cKullanici->Durum()->setSecilen()->getSelect("ID", "AD")?>
                            </select>
                            <label>Durum</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <input type="file" class="form-control" name="resim" id="resim">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="sira" name="sira" class="form-control" maxlength="3"/>
                            <label>Sıra</label>
                        </div>
                    </div>
                    <div class="col-12 text-right d-flex flex-wrap justify-content-end gap-4 row-gap-4">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-danger" onclick="fncKategoriResimSil(this)">Resmi Sil</button>
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Kapat">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $("#ustkategoriEkle").on("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        formData.append("controller", "kategori");
        formData.append("action", "ust_kategori_ekle");

        showSpinner();

        $.ajax({
            url: "/router.php",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    notyf.error(response.ACIKLAMA);
                } else {
                    notyf.success(response.ACIKLAMA);
                    location.reload(true);
                }
            },
            error: function(xhr, status, error) {
                $.unblockUI();
                notyf.error("Bir hata oluştu: " + error);
            }
        });
    });


    $("#kategoriEkle").on("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        formData.append("controller", "kategori");
        formData.append("action", "kategori_ekle");

        showSpinner();

        $.ajax({
            url: "/router.php",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    notyf.error(response.ACIKLAMA);
                } else {
                    notyf.success(response.ACIKLAMA);
                    location.reload(true);
                }
            },
            error: function(xhr, status, error) {
                $.unblockUI();
                notyf.error("Bir hata oluştu: " + error);
            }
        });
    });

    function fncKategoriBilgisi(obj){
        showSpinner();
        $.ajax({
            url: "/router.php",
            type: "POST",
            data: {id: $(obj).data("id"), controller: "kategori", action: "kategori_bilgisi"},
            dataType: "json",
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    Swal.fire({title: 'Uyarı!',text: response.ACIKLAMA ,icon: 'warning' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                } else {
                    $("#kategoriDuzenleModal #id").val(response.ROW.ID);
                    $("#kategoriDuzenleModal #kategori").val(response.ROW.KATEGORI);
                    $("#kategoriDuzenleModal #kategori_eng").val(response.ROW.KATEGORI_ENG);
                    $("#kategoriDuzenleModal #ust_kategori_id2").val(response.ROW.UST_KATEGORI_ID).trigger("change");
                    $("#kategoriDuzenleModal #sira").val(response.ROW.SIRA);
                    $("#kategoriDuzenleModal #durum4").val(response.ROW.DURUM).trigger('change');
                    $("#kategoriDuzenleModal #mevcutResim").attr("src", response.ROW.RESIM_URL);
                }
            }
        });
    }

    $("#kategoriDuzenle").on("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        formData.append("controller", "kategori");
        formData.append("action", "kategori_kaydet");

        showSpinner();

        $.ajax({
            url: "/router.php",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    notyf.error(response.ACIKLAMA);
                } else {
                    notyf.success(response.ACIKLAMA);
                    location.reload(true);
                }
            },
            error: function(xhr, status, error) {
                $.unblockUI();
                notyf.error("Bir hata oluştu: " + error);
            }
        });
    });

    function fncKategoriSil(obj){
        sweatAlert("Emin Misiniz?", "Evet, Sil").then(function (result) {
            if (result.value) {
                showSpinner();
                $.ajax({
                    url: "/router.php",
                    type: "POST",
                    data: {id: $(obj).data("id"), controller: "kategori", action: "kategori_sil"},
                    dataType: "json",
                    success: function(response) {
                        $.unblockUI();
                        if (response.HATA) {
                            Swal.fire({title: 'Uyarı!',text: response.ACIKLAMA ,icon: 'warning' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                        } else {
                            Swal.fire({title: 'Başarılı!',text: response.ACIKLAMA ,icon: 'success' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                            location.reload(true);
                        }
                    }
                });
            }
        });
    }

    function fncUstKategoriBilgisi(obj){
        showSpinner();
        $.ajax({
            url: "/router.php",
            type: "POST",
            data: {id: $(obj).data("id"), controller: "kategori", action: "ust_kategori_bilgisi"},
            dataType: "json",
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    Swal.fire({title: 'Uyarı!',text: response.ACIKLAMA ,icon: 'warning' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                } else {
                    $("#ustkategoriDuzenleModal #id").val(response.ROW.ID);
                    $("#ustkategoriDuzenleModal #ust_kategori").val(response.ROW.UST_KATEGORI);
                    $("#ustkategoriDuzenleModal #ust_kategori_eng").val(response.ROW.UST_KATEGORI_ENG);
                    $("#ustkategoriDuzenleModal #sira").val(response.ROW.SIRA);
                    $("#ustkategoriDuzenleModal #durum2").val(response.ROW.DURUM).trigger('change');
                    if (response.ROW.RESIM_URL) {
                        $("#ustkategoriDuzenleModal #mevcutResim").attr("src", response.ROW.RESIM_URL);
                    } else {
                        $("#ustkategoriDuzenleModal #mevcutResim").attr("src", "<?=$row_site->LOGO?>");
                    }
                }
            }
        });
    }

    $("#ustkategoriDuzenle").on("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        formData.append("controller", "kategori");
        formData.append("action", "ust_kategori_kaydet");

        showSpinner();

        $.ajax({
            url: "/router.php",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    notyf.error(response.ACIKLAMA);
                } else {
                    notyf.success(response.ACIKLAMA);
                    location.reload(true);
                }
            },
            error: function(xhr, status, error) {
                $.unblockUI();
                notyf.error("Bir hata oluştu: " + error);
            }
        });
    });
    
    function fncGizle(obj) {
        let ust_kategori = $(obj).data("ust_kategori");
        let targetRow = $(`.ust_kategori${ust_kategori}`);
        
        if (targetRow.is(":visible")) {
            targetRow.fadeOut();
        } else {
            targetRow.fadeIn();
        }
    }

    function fncUstKategoriSil(obj){
        sweatAlert("Emin Misiniz?", "Evet, Sil").then(function (result) {
            if (result.value) {
                showSpinner();
                $.ajax({
                    url: "/router.php",
                    type: "POST",
                    data: {id: $(obj).data("id"), controller: "kategori", action: "ust_kategori_sil"},
                    dataType: "json",
                    success: function(response) {
                        $.unblockUI();
                        if (response.HATA) {
                            Swal.fire({title: 'Uyarı!',text: response.ACIKLAMA ,icon: 'warning' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                        } else {
                            Swal.fire({title: 'Başarılı!',text: response.ACIKLAMA ,icon: 'success' ,customClass: {confirmButton: 'btn btn-primary waves-effect waves-light'},buttonsStyling: false});
                            location.reload(true);
                        }
                    }
                });
            }
        });
    }

    function fncUstKategoriResimSil(obj) {
        sweatAlert("Emin Misiniz?", "Evet, Sil").then(function (result) {
            if (result.value) {
                showSpinner();
                $.ajax({
                    url: "/router.php",
                    type: "POST",
                    data: $("#ustkategoriDuzenle").serialize() + "&controller=kategori&action=ust_kategori_resim_sil",
                    dataType: 'json',
                    success: function(response) {
                        $.unblockUI();
                        if (response.HATA) {
                            notyf.error(response.ACIKLAMA);
                        } else {
                            notyf.success(response.ACIKLAMA);
                            location.reload(true);
                        }
                    }
                });
            }
        });
    }

    function fncKategoriResimSil(obj) {
        sweatAlert("Emin Misiniz?", "Evet, Sil").then(function (result) {
            if (result.value) {
                showSpinner();
                $.ajax({
                    url: "/router.php",
                    type: "POST",
                    data: $("#kategoriDuzenle").serialize() + "&controller=kategori&action=kategori_resim_sil",
                    dataType: 'json',
                    success: function(response) {
                        $.unblockUI();
                        if (response.HATA) {
                            notyf.error(response.ACIKLAMA);
                        } else {
                            notyf.success(response.ACIKLAMA);
                            location.reload(true);
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $("tbody.sortable").each(function() {
            $(this).sortable({
                handle: "td",
                update: function() {
                    let siralama = [];

                    $(this).children("tr").each(function(index) {
                        siralama.push({
                            id: $(this).data("id"),
                            sira: index + 1
                        });
                    });

                    showSpinner();
                    $.ajax({
                        url: "/router.php",
                        type: "POST",
                        data: {controller: "kategori", action: "kategori_sira_kaydet", siralama: JSON.stringify(siralama)},
                        dataType: 'json',
                        success: function(response) {
                            $.unblockUI();
                            if (response.HATA) {
                                notyf.error(response.ACIKLAMA);
                            } else {
                                notyf.success(response.ACIKLAMA);
                                $(".sortable").html(response.HTML);
                            }
                        },
                        error: function() {
                            $.unblockUI();
                            notyf.error("Sunucu hatası oluştu.");
                        }
                    });
                }
            });
        });
    });

</script>