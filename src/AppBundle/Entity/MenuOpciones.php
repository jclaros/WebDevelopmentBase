<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuOpciones
 *
 * @ORM\Table(name="menu_opciones", uniqueConstraints={@ORM\UniqueConstraint(name="menu_opciones_pk", columns={"id_opc"})}, indexes={@ORM\Index(name="relationship_1_fk", columns={"id_padre"})})
 * @ORM\Entity
 */
class MenuOpciones
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_opc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="menu_opciones_id_opc_seq", allocationSize=1, initialValue=1)
     */
    private $idOpc;

    /**
     * @var string
     *
     * @ORM\Column(name="opcion", type="string", length=100, nullable=true)
     */
    private $opcion;

    /**
     * @var string
     *
     * @ORM\Column(name="controlador", type="string", length=255, nullable=true)
     */
    private $controlador;

    /**
     * @var string
     *
     * @ORM\Column(name="tooltip", type="string", length=255, nullable=true)
     */
    private $tooltip;

    /**
     * @var string
     *
     * @ORM\Column(name="icono", type="string", length=100, nullable=true)
     */
    private $icono;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=10, nullable=true)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="creacion", type="string", length=10, nullable=true)
     */
    private $creacion;

    /**
     * @var \MenuOpciones
     *
     * @ORM\ManyToOne(targetEntity="MenuOpciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_padre", referencedColumnName="id_opc")
     * })
     */
    private $idPadre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Perfiles", inversedBy="idOpc")
     * @ORM\JoinTable(name="perfiles_opciones",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_opc", referencedColumnName="id_opc")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_perfil", referencedColumnName="id_perfil")
     *   }
     * )
     */
    private $idPerfil;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idPerfil = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getOpcion()
    {
        return $this->opcion;
    }

    /**
     * @param string $opcion
     */
    public function setOpcion($opcion)
    {
        $this->opcion = $opcion;
    }

    /**
     * @return string
     */
    public function getControlador()
    {
        return $this->controlador;
    }

    /**
     * @param string $controlador
     */
    public function setControlador($controlador)
    {
        $this->controlador = $controlador;
    }

    /**
     * @return string
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * @return string
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * @param string $icono
     */
    public function setIcono($icono)
    {
        $this->icono = $icono;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param int $orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * @return string
     */
    public function getCreacion()
    {
        return $this->creacion;
    }

    /**
     * @param string $creacion
     */
    public function setCreacion($creacion)
    {
        $this->creacion = $creacion;
    }

    /**
     * @return \MenuOpciones
     */
    public function getIdPadre()
    {
        return $this->idPadre;
    }

    /**
     * @param \MenuOpciones $idPadre
     */
    public function setIdPadre($idPadre)
    {
        $this->idPadre = $idPadre;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idPerfil
     */
    public function setIdPerfil($idPerfil)
    {
        $this->idPerfil = $idPerfil;
    }

}
