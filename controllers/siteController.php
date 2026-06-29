<?

require_once ($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');

class SiteController {

    private $select;

    public function __construct($select = "") {
        $this->select       = $select;
    }

    public function sayfalamaOlustur($toplamVeri, $request, $sayfaBasinaVeri = 10){
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseUrl = $protocol . $host . $scriptName;

        $gecerliSayfa = isset($request['page']) ? (int) $request['page'] : 1;
        $request['page'] = null;

        $queryString = http_build_query(array_filter($request, function ($value) {return $value !== null;}));
        $url = $baseUrl . '?' . $queryString;
        return new Sayfalama($toplamVeri, $sayfaBasinaVeri, $gecerliSayfa, $url);
    }

    public function site_kaydet() {
        global $cResim;

        $data = array();
        $sql = "SELECT * FROM SITE WHERE ID = :ID";
        $data[":ID"]  = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if(is_null($row->ID)){
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Site Bilgileri Bulunamadı!";
            return $result;
        }

        $data = array();
        $sql = "UPDATE SITE SET FIRMA           = :FIRMA,
                                AD              = :AD,
                                SOYAD           = :SOYAD,
                                TELEFON         = :TELEFON,
                                MAIL            = :MAIL,
                                IL_ID           = :IL_ID,
                                ILCE_ID         = :ILCE_ID,
                                ADRES           = :ADRES,
                                TEMA_RENK       = :TEMA_RENK,
                                TITLE           = :TITLE,
                                YONETIM_URL     = :YONETIM_URL,
                                QR_URL          = :QR_URL,
                                GTARIH          = NOW()
                            WHERE ID = :ID
                            ";
        $data[":FIRMA"]             = trim($_REQUEST['firma']);
        $data[":AD"]                = trim($_REQUEST['ad']);
        $data[":SOYAD"]             = trim($_REQUEST['soyad']);
        $data[":TELEFON"]           = $_REQUEST['telefon'];
        $data[":MAIL"]              = trim($_REQUEST['mail']);
        $data[":IL_ID"]             = $_REQUEST['il_id'];
        $data[":ILCE_ID"]           = $_REQUEST['ilce_id'];
        $data[":ADRES"]             = trim($_REQUEST['adres']);
        $data[":TEMA_RENK"]         = $_REQUEST['tema_renk'];
        $data[":TITLE"]             = trim($_REQUEST['title']);
        $data[":YONETIM_URL"]       = trim($_REQUEST['yonetim_url']);
        $data[":QR_URL"]            = trim($_REQUEST['qr_url']);
        $data[":ID"]                = $row->ID;
        $update = DB::exec($sql, $data);

        if (isset($_FILES['resim']) AND $_FILES['resim']['error'] == 0) {

            $yol    = "img/site/{$row->ID}/";
            $resim  = $cResim->fncTekResimYukle($yol, $_FILES['resim']);

            $data = array();
            $sql = "UPDATE SITE SET LOGO = :LOGO WHERE ID = :ID";
            $data[":LOGO"]   = '/' . $yol . $resim["RESIM_ADI"];
            $data[":ID"]     = $row->ID;
            $resim = DB::exec($sql, $data);

            //Eski Resimi Siliyoruz
            if($resim > 0) unlink(fncDocumentRoot($row->LOGO));
        }

        if (isset($_FILES['favicon']) AND $_FILES['favicon']['error'] == 0) {

            $yol    = "img/site/{$row->ID}/";
            $resim  = $cResim->fncTekResimYukle($yol, $_FILES['favicon']);

            $data = array();
            $sql = "UPDATE SITE SET FAVICON = :FAVICON WHERE ID = :ID";
            $data[":FAVICON"]   = '/' . $yol . $resim["RESIM_ADI"];
            $data[":ID"]     = $row->ID;
            $resim = DB::exec($sql, $data);

            //Eski Resimi Siliyoruz
            if($resim > 0) unlink(fncDocumentRoot($row->FAVICON));
        }

        if($update > 0){
            $result["HATA"]      = FALSE;
            $result["ACIKLAMA"]  = "Kayıt Edildi.";
        }else{
            $result["HATA"]      = TRUE;
            $result["ACIKLAMA"]  = "Hata Oluştu.";
        }

        return $result;
    }

    public function resim_yukle() {
        global $cResim, $row_site;

        $data = array();
        $sql = "SELECT 
                    S.ID,
                    YEAR(S.TARIH) AS YIL
                FROM SITE AS S
                WHERE S.ID = :ID
                ";
        $data[":ID"]  = 1;
        $row = DB::getRow($sql, $data);

        if(is_null($row->ID)){
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Site Bulunamadı!";
            return $result;
        }

        if(is_null($_FILES['files'])){
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Resim Bulunamadı!";
            return $result;
        }

        $yol      = fncImgPathFirma($row_site->IMG_PATH, 'site', $row->ID, $row->YIL);
        $resimler = $cResim->fncResimYukle($yol, $_FILES['files']);
        
        $say = 0;
        foreach ($resimler as $key => $resim) {
            $data = array();
            $sql = "INSERT INTO SITE_RESIM SET  SITE_ID         = :SITE_ID,
                                                RESIM_ADI       = :RESIM_ADI,
                                                RESIM_ADI_ILK   = :RESIM_ADI_ILK,
                                                KAYIT_YAPAN_ID  = :KAYIT_YAPAN_ID
                                                ";
            $data[":SITE_ID"]         = $row->ID;
            $data[':RESIM_ADI']       = $resim["RESIM_ADI"];
            $data[':RESIM_ADI_ILK']   = $resim["RESIM_ADI_ILK"];
            $data[":KAYIT_YAPAN_ID"]  = $_SESSION['kullanici_id'];
            $id = DB::insert($sql, $data);
            if ($id > 0)$say++;

            fncIslemLog($id, DB::getSQL($sql, $data), $row, __FUNCTION__, "SITE_RESIM", "CARI_DUZENLE");
        }

        $data = array();
        $sql = "SELECT COUNT(*) AS SAY FROM SITE_RESIM WHERE SITE_ID = :SITE_ID";
        $data[":SITE_ID"]         = $row->ID;
        $row_resim_say = DB::getRow($sql, $data);

        if($row_resim_say->SAY == 1){
            $data = array();
            $sql = "UPDATE SITE SET VITRIN = 1 WHERE SITE_ID = :SITE_ID";
            $data[":SITE_ID"]   = $row->ID;
            DB::exec($sql, $data);
        }

        if($say > 0){
            $result["HATA"]      = FALSE;
            $result["ACIKLAMA"]  = "Resimler Yüklendi.";
        }else{
            $result["HATA"]      = TRUE;
            $result["ACIKLAMA"]  = "Hata Oluştu.";
        }

        return $result;
    }

    public function getSiteResimler($request) {
        global $row_site;

        $data = array();
        $sql = "SELECT 
                    SR.*,
                    CONCAT('site/', S.ID, '/', YEAR(S.TARIH), '/', SR.RESIM_ADI) AS RESIM_URL,
                    CONCAT_WS(' ',KU.AD,KU.SOYAD) AS KAYIT_YAPAN
                FROM SITE_RESIM AS SR
                    LEFT JOIN SITE AS S ON S.ID = SR.SITE_ID
                    LEFT JOIN KULLANICI AS KU ON KU.ID = SR.KAYIT_YAPAN_ID
                WHERE S.ID = 1
                ";

        $rows = DB::get($sql, $data);
        return $rows;
    }

    public function vitrin_yap() {

        if (!in_array($_SESSION['yetki_id'],array(1,2))) {
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Yetkiniz Yok!";
            return $result;
        }
        
        $data = array();
        $sql = "SELECT * FROM SITE_RESIM WHERE ID = :ID";
        $data[":ID"]  = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if(is_null($row->ID)){
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Resim Bulunamadı!";
            return $result;
        }

        $data = array();
        $sql = "UPDATE SITE_RESIM SET VITRIN = 0 WHERE SITE_ID = :SITE_ID";
        $data[":SITE_ID"]        = $row->SITE_ID;
        $update = DB::exec($sql, $data);

        $data = array();
        $sql = "UPDATE SITE_RESIM SET VITRIN = 1 WHERE ID = :ID";
        $data[":ID"]        = $row->ID;
        $update = DB::exec($sql, $data);

        if($update > 0){
            $result["HATA"]      = FALSE;
            $result["ACIKLAMA"]  = "Vitrine Alındı.";
        }else{
            $result["HATA"]      = TRUE;
            $result["ACIKLAMA"]  = "Hata Oluştu.";
        }

        return $result;
    }

    public function resim_sil() {
        global $row_site;

        if (!in_array($_SESSION['yetki_id'],array(1,2))) {
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Yetkiniz Yok!";
            return $result;
        }
        
        $data = array();
        $sql = "SELECT 
                    SR.*,
                    CONCAT('site/', S.ID, '/', YEAR(S.TARIH), '/', SR.RESIM_ADI) AS RESIM_URL
                FROM SITE_RESIM AS SR
                    LEFT JOIN SITE AS S ON S.ID = SR.SITE_ID
                WHERE SR.ID = :ID";
        $data[":ID"]  = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if(is_null($row->ID)){
            $result["HATA"]          = TRUE;
            $result["ACIKLAMA"]      = "Resim Bulunamadı!";
            return $result;
        }
        
        $data = array();
        $sql = "DELETE FROM SITE_RESIM WHERE ID = :ID";
        $data[":ID"]        = $row->ID;
        $delete = DB::exec($sql, $data);

        fncIslemLog($row->ID, DB::getSQL($sql, $data), $row, __FUNCTION__, "SITE_RESIM", "CARI_DUZENLE");

        if($delete > 0){
            $result["HATA"]      = FALSE;
            $result["ACIKLAMA"]  = "Silindi.";
            unlink(fncImgPathFolder2($row->RESIM_URL, $row_site->IMG_PATH));
        }else{
            $result["HATA"]      = TRUE;
            $result["ACIKLAMA"]  = "Hata Oluştu.";
        }

        return $result;
    }
}