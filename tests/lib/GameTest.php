<?php
require_once 'lib/Game.php';
class GameTest extends PHPUnit_Framework_TestCase {
	public $game;

	/**
	 * SetUp method
	 */
	public function setUp() {
		$this->game = new Game();
	}

	/**
	 * Test initial values after constructor
	 */
	public function testInitialValues() {
		$this->assertEquals($this->game->getNumberOfCards(), Game::NUMBER_OF_CARDS);
		$this->assertEquals($this->game->getNumberOfRows(), Game::NUMBER_OF_ROWS);
		$this->assertEquals($this->game->getNumberOfColumns(), Game::NUMBER_OF_COLUMNS);
		$this->assertEquals($this->game->getRemainingCards(), Game::NUMBER_OF_CARDS);
		$this->assertEquals($this->game->getAttempt(), 0);
		$this->assertNull($this->game->getPreviousIndex());
		$this->assertNull($this->game->getCurrentIndex());
		$this->assertEquals(count($this->game->getBoard()), Game::NUMBER_OF_CARDS * 2);
	}

	/**
	 * tests getting a new Card instance
	 * @covers Game::getCardInstance
	 */
	public function testGetCardInstance() {
		$newCard = $this->game->getCardInstance(1);
		$this->assertInstanceOf('Card', $newCard);
	}

	/**
	 * tests 2 unfolded cards match
	 * @covers Game::uncoverCard
	 * @covers Game::isMatch
	 */
	public function testUncoverTwoIdenticalCards() {
		$game = $this->game;
		$board = $game->getBoard();
		$realImage = $board[1]->getImage();
		$board[2]->setImage($realImage);
		$game->setBoard($board);
		$game->uncoverCard(1);
		$result = $game->uncoverCard(2);
		$expected = ['attempt' => 0, 'isMatch' => true,
					'currentImage' => $realImage, 'remainingCards' => $game->getNumberOfCards() - 1];
		$this->assertEquals($expected, $result);
		$this->assertNull($this->game->getPreviousIndex());
		$this->assertNull($this->game->getCurrentIndex());
		$this->assertEquals($this->game->getAttempt(), 0);
	}

	/**
	 * tests 2 unfolded cards do not match
	 * @covers Game::uncoverCard
	 * @covers Game::isMatch
	 */
	public function testNoMatchingCards() {
		$game = $this->game;
		$board = $game->getBoard();
		$image = 'imposible to match';
		$board[1]->setImage($image);
		$result = $game->uncoverCard(2);
		$expected = ['attempt' => 1, 'isMatch' => false,
			'currentImage' => $board[2]->getImage(), 'remainingCards' => $game->getNumberOfCards()];
		$this->assertEquals($result, $expected);
		$result = $game->uncoverCard(1);
		$expected = ['attempt' => 0, 'isMatch' => false,
			'currentImage' => $image, 'remainingCards' => $game->getNumberOfCards()];
		$this->assertEquals($result, $expected);
		$this->assertNull($this->game->getPreviousIndex());
		$this->assertNull($this->game->getCurrentIndex());
	}
}