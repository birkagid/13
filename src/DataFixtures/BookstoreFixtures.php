<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Book;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookstoreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Создаём клиентов
        $customers = [];
        $customerData = [
            ['name' => 'Виталий Калашников', 'email' => 'cazino@example.com'],
            ['name' => 'Анатолий Ермак', 'email' => 'omega@example.com'],
            ['name' => 'Богдан Башмаков', 'email' => 'pecatiny@example.com'],
            ['name' => 'Григорий Левин', 'email' => 'opera@example.com'],
        ];

        foreach ($customerData as $data) {
            $customer = new Customer();
            $customer->setName($data['name']);
            $customer->setEmail($data['email']);
            $manager->persist($customer);
            $customers[] = $customer;
        }

        // Создаём книги
        $books = [];
        $bookData = [
            ['title' => 'Унесённые ветром', 'price' => '10000.00'],
            ['title' => 'Три мушкетёра', 'price' => '420.00'],
            ['title' => 'Отцы и дети', 'price' => '4242.00'],
            ['title' => 'Герой нашего времени', 'price' => '300.00'],
            ['title' => 'Война и мир', 'price' => '1999.00'],
        ];

        foreach ($bookData as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setPrice($data['price']);
            $manager->persist($book);
            $books[] = $book;
        }

        // Создаём заказы
        $order1 = new Order();
        $order1->setCustomer($customers[0]);
        $order1->addBook($books[0]);
        $order1->addBook($books[1]);
        $order1->addBook($books[2]);
        $order1->calculateTotal();
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setCustomer($customers[1]);
        $order2->addBook($books[3]);
        $order2->addBook($books[2]);
        $order2->calculateTotal();
        $manager->persist($order2);
        
        $order3 = new Order();
        $order3->setCustomer($customers[0]);
        $order3->addBook($books[4]);
        $order3->calculateTotal();
        $manager->persist($order3);

        $manager->flush();
    }
}