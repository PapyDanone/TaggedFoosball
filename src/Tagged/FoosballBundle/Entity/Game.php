<?php

namespace Tagged\FoosballBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tagged\FoosballBundle\Entity\GameRepository")
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player1_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $player1;

    /**
     * @var integer
     *
     * @ORM\Column(name="Score1", type="integer")
     */
    private $score1;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player2_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $player2;

    /**
     * @var integer
     *
     * @ORM\Column(name="Score2", type="integer")
     */
    private $score2;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set score1
     *
     * @param integer $score1
     * @return Game
     */
    public function setScore1($score1)
    {
        $this->score1 = $score1;

        return $this;
    }

    /**
     * Get score1
     *
     * @return integer
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Set score2
     *
     * @param integer $score2
     * @return Game
     */
    public function setScore2($score2)
    {
        $this->score2 = $score2;

        return $this;
    }

    /**
     * Get score2
     *
     * @return integer
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Set player1
     *
     * @param \Tagged\FoosballBundle\Entity\Player $player1
     * @return Game
     */
    public function setPlayer1(\Tagged\FoosballBundle\Entity\Player $player1)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * Get player1
     *
     * @return \Tagged\FoosballBundle\Entity\Player
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * Set player2
     *
     * @param \Tagged\FoosballBundle\Entity\Player $player2
     * @return Game
     */
    public function setPlayer2(\Tagged\FoosballBundle\Entity\Player $player2)
    {
        $this->player2 = $player2;

        return $this;
    }

    /**
     * Get player2
     *
     * @return \Tagged\FoosballBundle\Entity\Player
     */
    public function getPlayer2()
    {
        return $this->player2;
    }
}
