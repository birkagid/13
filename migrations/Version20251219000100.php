<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251219000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bookstore: customers, books, orders, order_book';
    }

    public function up(Schema $schema): void
    {
        // customers
        $this->addSql('
            CREATE TABLE customer (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                UNIQUE INDEX UNIQ_CUSTOMER_EMAIL (email),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        // books
        $this->addSql('
            CREATE TABLE book (
                id INT AUTO_INCREMENT NOT NULL,
                title VARCHAR(255) NOT NULL,
                price NUMERIC(10, 2) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        // orders
        $this->addSql('
            CREATE TABLE `order` (
                id INT AUTO_INCREMENT NOT NULL,
                customer_id INT NOT NULL,
                total_amount NUMERIC(10, 2) NOT NULL,
                order_date DATETIME NOT NULL,
                INDEX IDX_ORDER_CUSTOMER (customer_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        // order_book (many-to-many)
        $this->addSql('
            CREATE TABLE order_book (
                order_id INT NOT NULL,
                book_id INT NOT NULL,
                INDEX IDX_ORDER_BOOK_ORDER (order_id),
                INDEX IDX_ORDER_BOOK_BOOK (book_id),
                PRIMARY KEY(order_id, book_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        // foreign keys
        $this->addSql('
            ALTER TABLE `order`
            ADD CONSTRAINT FK_ORDER_CUSTOMER
            FOREIGN KEY (customer_id) REFERENCES customer (id)
            ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE order_book
            ADD CONSTRAINT FK_ORDER_BOOK_ORDER
            FOREIGN KEY (order_id) REFERENCES `order` (id)
            ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE order_book
            ADD CONSTRAINT FK_ORDER_BOOK_BOOK
            FOREIGN KEY (book_id) REFERENCES book (id)
            ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_book DROP FOREIGN KEY FK_ORDER_BOOK_ORDER');
        $this->addSql('ALTER TABLE order_book DROP FOREIGN KEY FK_ORDER_BOOK_BOOK');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_ORDER_CUSTOMER');

        $this->addSql('DROP TABLE order_book');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE customer');
    }
}
