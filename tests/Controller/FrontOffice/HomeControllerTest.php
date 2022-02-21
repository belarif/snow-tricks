<?php declare(strict_types=1);

namespace App\Tests\Controller\FrontOffice;

use App\DataFixtures\TrickFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test case
 */
final class HomeControllerTest extends WebTestCase {
	public function test_it_display_home_page(): void {
		$client = $this->createClient();
		$crawler = $client->request('GET', '/snow-tricks/accueil');

		$response = $client->getResponse();
		self::assertTrue($response->isOk());
		self::assertSame(
			'Bienvenue dans votre plateforme SNOW TRICKS',
			$crawler->filter('h1')->first()->text()
		);
	}

	public function test_it_list_all_tricks_on_home_page(): void {
		$client = $this->createClient();
		$crawler = $client->request('GET', '/snow-tricks/accueil');

		self::assertGreaterThanOrEqual(
			count(TrickFixtures::$tricks),
			$crawler->filter('.card-trick')->count()
		);
	}

	public function test_it_display_tricks_with_image(): void {
		$client = $this->createClient();
		$crawler = $client->request('GET', '/snow-tricks/accueil');

		self::assertGreaterThanOrEqual(
			1,
			$crawler->filter('.card-trick img')->first()->count()
		);
	}

}
