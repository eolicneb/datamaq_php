<?php
// Entidad Dashboard: representa los datos del panel de control en el dominio
class DashboardV2 {
    public $title;
    public $producto;
    public $turno;
    public $velUlt;
    public $unixtime;
    public $formato;
    public $bobina;
    public $rawdata;
    public $label;

    public function __construct(
            $title, $producto, $turno, $formato, $bobina,
            $velUlt, $unixtime, $rawdata, $label
        ) {
        //error_log(print_r($formato), print_r($rawdata));
        $this->title = $title;
        $this->producto = $producto;
        $this->turno = $turno;
        $this->formato = $formato;
        $this->bobina = $bobina;
        $this->velUlt = $velUlt;
        $this->unixtime = $unixtime;
        $this->rawdata = $rawdata;
        $this->label = $label;
    }
}
