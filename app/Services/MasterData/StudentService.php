<?php

namespace App\Services\MasterData;

use App\Repositories\StudentRepository;

class StudentService
{
    private StudentRepository $repository;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * handle sync student from dapodik
     *
     * @param array $students
     * @return void
     */
    public function handleSyncStudent(array $students): void
    {
        foreach ($students as $student) {
            $check = $this->repository->getStudentByNisn($student['nisn']);
            if($check) {
                $this->repository->updateStudent($check, $student);
            } else {
                $this->repository->insertStudent($student);
            }
        }
    }
}
