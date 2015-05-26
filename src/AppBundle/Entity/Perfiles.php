<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perfiles
 *
 * @ORM\Table(name="perfiles", uniqueConstraints={@ORM\UniqueConstraint(name="perfiles_pk", columns={"id_perfil"})})
 * @ORM\Entity
 */
class Perfiles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_perfil", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="perfiles_id_perfil_seq", allocationSize=1, initialValue=1)
     */
    private $idPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=10, nullable=true)
     */
    private $estado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="MenuOpciones", mappedBy="idPerfil")
     */
    private $idOpc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idOpc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdOpc()
    {
        return $this->idOpc;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idOpc
     */
    public function setIdOpc($idOpc)
    {
        $this->idOpc = $idOpc;
    }

}
