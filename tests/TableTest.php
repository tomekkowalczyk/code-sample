<?php

namespace Tests\...;


use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    /** @var Table */
    private $table;

    public function setUp()
    {
        // Given
        $this->table = new Table();
    }

    public function testShouldCreateEmptyTable()
    {
        // When
        $actual = $this->table->countPlayers();

        // Then
        $this->assertSame(0, $actual);
    }

    public function testShouldAddOnePlayerToTable()
    {
       // Given
        $player = new Player('John');

        // When
        $this->table->addPlayer($player);
        $actual = $this->table->countPlayers();

        // Then
        $this->assertSame(1, $actual);
    }

    public function testShouldReturnCountWhenAddManyPlayerToTable()
    {
        // When
        $this->table->addPlayer(new Player('John'));
        $this->table->addPlayer(new Player('Tom'));

        $actual = $this->table->countPlayers();

        // Then
        $this->assertSame(2, $actual);
    }

    public function testShouldThrowTooManyPlayerToTableWhenAddMoreFourPlayers()
    {
       // Expect
        $this->expectException(TooManyPlayersAtTheTableException::class);
        $this->expectExceptionMessage('Max capacity is 4 players');

        // When
        $this->table->addPlayer(new Player('John'));
        $this->table->addPlayer(new Player('Tom'));
        $this->table->addPlayer(new Player('Anna'));
        $this->table->addPlayer(new Player('Andy'));
        $this->table->addPlayer(new Player('Jacobs'));
    }


    public function testShouldReturnEmptyCardCollectionForPlayedCard() {
        // When
        $actual = $this->table->getPlayedCards();

        // Then
        $this->assertInstanceOf(CardCollection::class, $actual);
        $this->assertCount(0, $actual);
    }

    public function testShouldPutCardDeckOnTable() {
        // Given
        $cards = new CardCollection([
            new Card(Card::COLOR_DIAMOND, Card::VALUE_KING)
        ]);

        // When
        $table = new Table($cards);
        $actual = $table->getCardDeck();

        // Then
        $this->assertSame($cards, $actual);
    }

    public function testShouldAddCardCollectionToCardDeckOnTable()
    {
        // Given
        $cardCollection = new CardCollection([
            new Card(Card::COLOR_DIAMOND, Card::VALUE_KING),
            new Card(Card::COLOR_HEART, Card::VALUE_EIGHT),
        ]);

        // When
        $actual = $this->table->addCardCollectionToDeck($cardCollection);

        // Then
        $this->assertEquals($cardCollection, $actual->getCardDeck());
    }

    public function testShouldReturnCurrentPlayer()
    {
        // Given
        $player1 = new Player('Andy');
        $player2 = new Player('Tom');
        $player3 = new Player('Jack');

        $this->table->addPlayer($player1);
        $this->table->addPlayer($player2);
        $this->table->addPlayer($player3);

        // When
        $actual = $this->table->getCurrentPlayer();

        // Then
        $this->assertSame($player1, $actual);
    }

    public function testShouldReturnNextPlayer()
    {
        // Given
        $player1 = new Player('Andy');
        $player2 = new Player('Tom');
        $player3 = new Player('Jack');

        $this->table->addPlayer($player1);
        $this->table->addPlayer($player2);
        $this->table->addPlayer($player3);

        // When
        $actual = $this->table->getNextPlayer();

        // Then
        $this->assertSame($player2, $actual);
    }

    public function testShouldReturnPreviousPlayer()
    {
        // Given
        $player1 = new Player('Andy');
        $player2 = new Player('Tom');
        $player3 = new Player('Jack');
        $player4 = new Player('Bill');

        $this->table->addPlayer($player1);
        $this->table->addPlayer($player2);
        $this->table->addPlayer($player3);
        $this->table->addPlayer($player4);

        // When
        $actual = $this->table->getPreviousPlayer();

        // Then
        $this->assertSame($player4, $actual);
    }

    public function testShouldSwitchCurrentPlayerWhenRoundFinished()
    {
        // Given
        $player1 = new Player('Andy');
        $player2 = new Player('Tom');
        $player3 = new Player('Jack');

        $this->table->addPlayer($player1);
        $this->table->addPlayer($player2);
        $this->table->addPlayer($player3);

        // When & Then
        $this->assertSame($player1, $this->table->getCurrentPlayer());
        $this->assertSame($player2, $this->table->getNextPlayer());
        $this->assertSame($player3, $this->table->getPreviousPlayer());

        $this->table->finishRound();

        $this->assertSame($player2, $this->table->getCurrentPlayer());
        $this->assertSame($player3, $this->table->getNextPlayer());
        $this->assertSame($player1, $this->table->getPreviousPlayer());

        $this->table->finishRound();

        $this->assertSame($player3, $this->table->getCurrentPlayer());
        $this->assertSame($player1, $this->table->getNextPlayer());
        $this->assertSame($player2, $this->table->getPreviousPlayer());

        $this->table->finishRound();

        $this->assertSame($player1, $this->table->getCurrentPlayer());
        $this->assertSame($player2, $this->table->getNextPlayer());
        $this->assertSame($player3, $this->table->getPreviousPlayer());
    }

}