<?php

namespace spec\Eighty8\LaravelSeeder;

use Illuminate\Database\ConnectionResolverInterface;
use PhpSpec\ObjectBehavior;

class SeederRepositorySpec extends ObjectBehavior
{
    /** @var string */
    protected $table = 'test-table-name';

    /** @var string */
    protected $source = 'mysql';

    /** @var string */
    protected $env = 'production';

    /** @var ConnectionResolverInterface */
    protected $resolver;

    public function let(ConnectionResolverInterface $resolver)
    {
        $this->resolver = $resolver;

        $this->beConstructedWith($this->resolver, $this->table);
        $this->setSource($this->source);
        $this->setEnv($this->env);

        $this->shouldHaveType('Eighty8\LaravelSeeder\SeederRepository');
    }

    public function it_should_implement_interface()
    {
        $this->beAnInstanceOf('Illuminate\Database\Migrations\MigrationRepositoryInterface');
    }

    public function it_should_provide_resolver()
    {
        $this->getConnectionResolver()->shouldBe($this->resolver);
    }

    public function it_should_get_connection_for_source()
    {
        $connection = microtime();
        $this->resolver->connection($this->source)->willReturn($connection);

        $this->getConnection()->shouldBe($connection);
    }
}
