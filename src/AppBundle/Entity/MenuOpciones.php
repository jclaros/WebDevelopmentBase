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

}
