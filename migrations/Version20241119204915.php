<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241119204915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE table users
            (
                id int auto_increment,
                name varchar(64) not null,
                email varchar(256) not null,
                created DATETIME not null,
                deleted DATETIME null,
                notes TEXT null,
                constraint users_pk
                    primary key (id)
            );
            create unique index users_email_uindex
                on  users (email);
            create  unique index  users_name_uindex
                on  users (name);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP table users;');
    }
}
