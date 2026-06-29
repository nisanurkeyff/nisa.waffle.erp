<?
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');
    session_kontrol();

    $result = $cYorum->yorum_bilgisi();
    if($result["HATA"]){
    	echo $result["ACIKLAMA"];
    	exit;
    }
    
    $row = $result["ROW"];

?>
<!Doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template" data-style="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title> <?=$row_site->TITLE?> | Yorum Düzenle </title>
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
                            <div class="row gy-6 gy-md-0">
                                
                                <div class="col-xl-12">
                                    <div class="card mb-6">
                                        <div class="card-header overflow-hidden">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link waves-effect active" data-bs-toggle="tab" data-bs-target="#tab_yorum" role="tab" aria-selected="true">
                                                    <span class="ri-user-line ri-20px d-sm-none"></span><span class="d-none d-sm-block">Yorum Bilgisi</span></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade active show" id="tab_yorum" role="tabpanel">
                                                <form id="yorumKaydet">
                                                	<input type="hidden" name="id" value="<?=$row->ID?>">
                                                    <div class="row g-6">
                                                        <div class="col-md-6">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="ri-user-line"></i></span>
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" id="isim" name="isim" class="form-control" value="<?=$row->ISIM?>" placeholder="İsim">
                                                                    <label>İsim</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <?php
                                                                	$tarih_format = "";
                                                                	if(!empty($row->YORUM_TARIH) && $row->YORUM_TARIH != "0000-00-00" && $row->YORUM_TARIH != "0000-00-00 00:00:00"){
                                                                		$tarih_format = date("Y-m-d", strtotime($row->YORUM_TARIH));
                                                                	}
                                                                ?>
                                                                <input type="text" id="tarih" name="tarih" class="form-control datepicker active" value="<?=$tarih_format?>" placeholder="YYYY-MM-DD" readonly="readonly">
                                                                <label>Tarih</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <select name="yildiz" id="yildiz" class="select2 form-select" data-style="btn-default">
                                                                    <option value="5" <?=($row->YILDIZ==5 ? 'selected' : '')?>>5 Yıldız</option>
                                                                    <option value="4" <?=($row->YILDIZ==4 ? 'selected' : '')?>>4 Yıldız</option>
                                                                    <option value="3" <?=($row->YILDIZ==3 ? 'selected' : '')?>>3 Yıldız</option>
                                                                    <option value="2" <?=($row->YILDIZ==2 ? 'selected' : '')?>>2 Yıldız</option>
                                                                    <option value="1" <?=($row->YILDIZ==1 ? 'selected' : '')?>>1 Yıldız</option>
                                                                </select>
                                                                <label>Yıldız Sayısı</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <select name="durum" id="durum" class="select2 form-select" data-style="btn-default">
                                                                    <?=$cKullanici->Durum()->setSecilen($row->DURUM)->getSelect("ID", "AD")?>
                                                                </select>
                                                                <label>Durum</label>
                                                            </div>
                                                        </div>
                                                        <div class="w-100"></div>
                                                        <div class="col-md-12">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="ri-chat-4-line"></i></span>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea class="form-control" id="yorum" name="yorum" placeholder="Yorum" style="height: 120px;"><?=$row->YORUM?></textarea>
                                                                    <label for="basic-icon-default-message">Yorum</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end pt-6">
                                                        <button type="submit" class="btn btn-primary me-4 waves-effect waves-light">Kaydet</button>
                                                        <a href="/views/yorum/yorum_listesi.php" class="btn btn-outline-secondary waves-effect">Vazgeç</a>
                                                    </div>
                                                </form>
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

<script type="text/javascript">

    $("#yorumKaydet").on("submit", function(event) {
        event.preventDefault();
        showSpinner();
        $.ajax({
            url: "/router.php",
            type: "POST",
            data: $(this).serialize() + "&controller=yorum&action=yorum_kaydet",
            dataType: "json",
            success: function(response) {
                $.unblockUI();
                if (response.HATA) {
                    notyf.error(response.ACIKLAMA);
                } else {
                    notyf.success(response.ACIKLAMA);
                    setTimeout(function(){
                    	location.href = response.URL;
                    }, 1000);
                }
            }
        });
    });

</script>
