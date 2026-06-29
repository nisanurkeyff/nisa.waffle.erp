<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/class/config.php');

class YorumController
{

    private $select;
    private $site;

    function __construct($select = "", $row_site = "")
    {
        global $row_site;
        $this->select = $select;
        $this->site = $row_site;
    }

    public function sayfalamaOlustur($toplamVeri, $request, $sayfaBasinaVeri = 10)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseUrl = $protocol . $host . $scriptName;

        $gecerliSayfa = isset($request['page']) ? (int)$request['page'] : 1;
        $request['page'] = null;

        $queryString = http_build_query(array_filter($request, function ($value) {
            return $value !== null;
        }));
        $url = $baseUrl . '?' . $queryString;
        return new Sayfalama($toplamVeri, $sayfaBasinaVeri, $gecerliSayfa, $url);
    }

    public function getYorumlar($request = array())
    {
        $data = array();

        $sql = "SELECT 
                    Y.* 
                FROM YORUM AS Y
                WHERE 1 ";

        if ($request["isim"] > 0) {
            $sql .= " AND Y.ISIM LIKE :ISIM ";
            $data[":ISIM"] = "%" . $request["isim"] . "%";
        }

        if (isset($request["durum"]) && $request["durum"] != "") {
            $sql .= " AND Y.DURUM = :DURUM ";
            $data[":DURUM"] = $request["durum"];
        }

        $sql .= " ORDER BY Y.ID DESC";

        $sayfalama = $this->sayfalamaOlustur(count2(DB::get($sql, $data)), $request, $request['sayfalama'] ? $request['sayfalama'] : 50);
        $excel_sql = DB::getSQL($sql, $data);

        $sql .= $sayfalama->getLimitOffset();
        $rows = DB::get($sql, $data);

        return [
            'rows' => $rows,
            'sayfalama' => $sayfalama,
            'limit' => $sayfalama->getLimitOffset(),
            'excel_sql' => $excel_sql,
            'sayfa_araligi' => $sayfalama->getGorunumAraligi()
        ];
    }

    public function yorum_bilgisi()
    {
        $data = array();
        $sql = "SELECT Y.* FROM YORUM AS Y WHERE Y.ID = :ID";
        $data[":ID"] = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if ($row->ID > 0) {
            $result["HATA"] = FALSE;
            $result["ACIKLAMA"] = "Yorum Bulundu.";
            $result["ROW"] = $row;
        }
        else {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Yorum Bulunamadı!";
        }

        return $result;
    }

    public function yorum_ekle()
    {
        if (strlen(trim($_REQUEST['isim'])) <= 0) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "İsim Giriniz!";
            return $result;
        }
        if (strlen(trim($_REQUEST['yorum'])) <= 0) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Yorum Giriniz!";
            return $result;
        }

        $data = array();
        $sql = "INSERT INTO YORUM SET   ISIM            = :ISIM,
                                        YORUM           = :YORUM,
                                        YORUM_TARIH     = :YORUM_TARIH,
                                        YILDIZ          = :YILDIZ,
                                        DURUM           = :DURUM
                                        ";
        $data[":ISIM"] = trim($_REQUEST['isim']);
        $data[":YORUM"] = trim($_REQUEST['yorum']);
        $data[":YORUM_TARIH"] = trim($_REQUEST['tarih']);
        $data[":YILDIZ"] = (int)$_REQUEST['yildiz'];
        $data[":DURUM"] = (int)$_REQUEST['durum'];

        $id = DB::insert($sql, $data);

        if ($id > 0) {
            $result["HATA"] = FALSE;
            $result["ACIKLAMA"] = "Yorum Eklendi.";
            $result["URL"] = "/views/yorum/yorum_listesi.php";
        }
        else {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Hata Oluştu.";
        }

        return $result;
    }

    public function yorum_kaydet()
    {
        $data = array();
        $sql = "SELECT * FROM YORUM WHERE ID = :ID";
        $data[":ID"] = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if (is_null($row->ID)) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Yorum Bulunamadı!";
            return $result;
        }

        if (strlen(trim($_REQUEST['isim'])) <= 0) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "İsim Giriniz!";
            return $result;
        }
        if (strlen(trim($_REQUEST['yorum'])) <= 0) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Yorum Giriniz!";
            return $result;
        }

        $data = array();
        $sql = "UPDATE YORUM SET    ISIM            = :ISIM,
                                    YORUM           = :YORUM,
                                    YORUM_TARIH     = :YORUM_TARIH,
                                    YILDIZ          = :YILDIZ,
                                    DURUM           = :DURUM
                                WHERE ID    = :ID
                                ";
        $data[":ISIM"] = trim($_REQUEST['isim']);
        $data[":YORUM"] = trim($_REQUEST['yorum']);
        $data[":YORUM_TARIH"] = trim($_REQUEST['tarih']);
        $data[":YILDIZ"] = (int)$_REQUEST['yildiz'];
        $data[":DURUM"] = (int)$_REQUEST['durum'];
        $data[":ID"] = $row->ID;
        $update = DB::exec($sql, $data);

        $result["HATA"] = FALSE;
        $result["ACIKLAMA"] = "Yorum Güncellendi.";
        $result["URL"] = "/views/yorum/yorum_listesi.php";

        return $result;
    }

    public function yorum_sil()
    {
        $data = array();
        $sql = "SELECT * FROM YORUM WHERE ID = :ID";
        $data[":ID"] = $_REQUEST["id"];
        $row = DB::getRow($sql, $data);

        if (is_null($row->ID)) {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Yorum Bulunamadı!";
            return $result;
        }

        $data = array();
        $sql = "DELETE FROM YORUM WHERE ID = :ID";
        $data[":ID"] = $row->ID;
        $delete = DB::exec($sql, $data);

        if ($delete > 0) {
            $result["HATA"] = FALSE;
            $result["ACIKLAMA"] = "Silindi.";
        }
        else {
            $result["HATA"] = TRUE;
            $result["ACIKLAMA"] = "Hata Oluştu.";
        }

        return $result;
    }
}
?>
