<?php
class AnalisisVenta {
    private $gananciasHoy;
    private $gananciasAyer;

    public function __construct($gananciasHoy = 0, $gananciasAyer = 0) {
        $this->gananciasHoy = $gananciasHoy;
        $this->gananciasAyer = $gananciasAyer;
    }

    public function calcularGananciasHoy() {
        return "Ganancias de hoy: " . $this->gananciasHoy;
    }

    public function calcularGananciasAyer() {
        return "Ganancias de ayer: " . $this->gananciasAyer;
    }

    public function compararGanancias() {
        $diferencia = $this->gananciasHoy - $this->gananciasAyer;
        if ($diferencia > 0) {
            return "Las ganancias de hoy han aumentado en $diferencia en comparación a ayer.";
        } elseif ($diferencia < 0) {
            return "Las ganancias de hoy han disminuido en " . abs($diferencia) . " en comparación a ayer.";
        } else {
            return "Las ganancias se mantienen estables con respecto a ayer.";
        }
    }

    public function mostrarTendencias() {
        return "Tendencias de ventas: Comparativa entre diferentes periodos de tiempo.";
    }
}
?>