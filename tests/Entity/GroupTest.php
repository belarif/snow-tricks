<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Group;
use PHPUnit\Framework\TestCase;

/**
 * Unit test case
 */
final class GroupTest extends TestCase {

	public function test_it_should_create_group_with_empty_name(): void {
		$group = new Group();
		self::assertNull($group->getName());
	}

	public function test_it_should_create_group_with_empty_id(): void {
		$group = new Group();
		self::assertNull($group->getId());
	}

	public function test_it_should_set_name_of_group(): void {
		$group = (new Group())
			->setName($name = uniqid());

		self::assertSame($name, $group->getName());
	}
}
