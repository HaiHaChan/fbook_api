<?php

namespace App\Contracts\Repositories;

use App\Eloquent\Book;
use App\Contracts\Repositories\MediaRepository;
use App\Contracts\Repositories\LogReputationRepository;

interface BookRepository extends AbstractRepository
{
    public function getDataInHomepage($with = [], $dataSelect = ['*'], $officeId = '');

    public function getBooksByFields($with = [], $dataSelect = ['*'], $field, $attribute = [], $officeId = '');

    public function getDataSearch(array $attribute, $with = [], $dataSelect = ['*'], $officeId = '');

    public function getDataSearchTitle(array $attribute, $with = [], $dataSelect = ['*'], $officeId = '');

    public function getDataSearchAuthor(array $attribute, $with = [], $dataSelect = ['*'], $officeId = '');

    public function getDataSearchDescription(array $attribute, $with = [], $dataSelect = ['*'], $officeId = '');

    public function getDataSearchUser(array $attribute, $with = []);

    public function booking(Book $book, array $data);

    public function review($bookId, array $data);

    public function getDataFilterInHomepage($with = [], $dataSelect = ['*'], $filters = [], $officeId = '');

    public function show($id);

    public function store(
        array $attributes,
        MediaRepository $mediaRepository,
        LogReputationRepository $logReputationRepository
    );

    public function destroy(Book $book);

    public function getBookByCategory($categoryId, $dataSelect = ['*'], $with = [], $officeId = '');

    public function getBookFilteredByCategory($categoryId, $attribute = [], $dataSelect = ['*'], $with = [], $officeId = '');

    public function increaseView(Book $book);

    public function addOwner($id, LogReputationRepository $logReputationRepository);

    public function removeOwner(Book $book);

    public function uploadMedia(Book $book, $attributes = [], MediaRepository $mediaRepository);

    public function checkApprove(Book $book, $attribute = []);

    public function approve(Book $book, $attribute = []);

    public function getBookByOffice($categoryId, $dataSelect = ['*'], $with = []);

    public function requestUpdateBook(array $attributes, Book $book, MediaRepository $mediaRepository);

    public function approveRequestUpdateBook($updateBookId);

    public function deleteRequestUpdateBook($updateBookId);

    public function countHaveBook();
    
    public function destroyBook(Book $deleteBook);
}
