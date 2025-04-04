<?php

class Employee {
    private string $name;
    private int $id;
    private Company $mainJob;
    private ?Company $secondJob;

    public function __construct(string $name, int $id, Company $mainJob, ?Company $secondJob = null) {
        $this->name = $name;
        $this->id = $id;
        $this->mainJob = $mainJob;
        $this->secondJob = $secondJob;
    }

    public function getDetails(): string {
        $jobs = $this->mainJob->getName();
        if ($this->secondJob) {
            $jobs .= ', ' . $this->secondJob->getName();
        }
        return "従業員名: {$this->name}, ID: {$this->id}, 勤務先: {$jobs}";
    }
}

class Company {
    private string $name;
    private array $employees = [];
    private array $boardMembers = [];
    private ?Company $parentCompany = null;
    private array $subsidiaries = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function addEmployee(Employee $employee): void {
        $this->employees[] = $employee;
    }

    public function setBoardMember(BoardMember $boardMember, int $position): void {
        if ($position >= 0 && $position < 10) {
            $this->boardMembers[$position] = $boardMember;
        }
    }

    public function setParentCompany(?Company $company): void {
        $this->parentCompany = $company;
    }

    public function addSubsidiary(Company $company): void {
        if (!in_array($company, $this->subsidiaries, true)) {
            $this->subsidiaries[] = $company;
            $company->setParentCompany($this);
        }
    }

    public function addSelfAsSubsidiary(): void {
        $this->addSubsidiary($this);
    }

    public function displayHierarchy(int $level = 0): void {
        echo str_repeat('--', $level) . $this->name . PHP_EOL;
        foreach ($this->subsidiaries as $subsidiary) {
            if ($subsidiary !== $this) {
                $subsidiary->displayHierarchy($level + 1);
            } else {
                echo str_repeat('--', $level + 1) . "(自己参照) {$this->name}" . PHP_EOL;
            }
        }
    }
}

class BoardMember {
    private string $name;
    private string $position;
    private array $companiesManaging = [];

    public function __construct(string $name, string $position) {
        $this->name = $name;
        $this->position = $position;
    }

    public function setCompany(Company $company, int $position): void {
        if ($position >= 0 && $position < 5) {
            $this->companiesManaging[$position] = $company;
        }
    }

    public function getDetails(): string {
        $companies = array_map(fn($c) => $c->getName(), $this->companiesManaging);
        $companiesList = implode(', ', $companies);
        return "役員名: {$this->name}, 役職: {$this->position}, 管理企業: {$companiesList}";
    }
}

// シミュレーションの実行
$company1 = new Company("会社A");
$company2 = new Company("会社B");
$company3 = new Company("会社C");

$employee = new Employee("山田太郎", 1001, $company1, $company2);
$company1->addEmployee($employee);
$company2->addEmployee($employee);

$boardMember = new BoardMember("佐藤一郎", "CEO");
$company1->setBoardMember($boardMember, 0);
$boardMember->setCompany($company1, 0);
$boardMember->setCompany($company2, 1);

$company1->addSubsidiary($company2);
$company2->addSubsidiary($company3);
$company3->addSelfAsSubsidiary();

// 組織構造の表示
echo "企業の階層構造:" . PHP_EOL;
$company1->displayHierarchy();

// 従業員と役員の詳細を表示
echo PHP_EOL . $employee->getDetails() . PHP_EOL;
echo $boardMember->getDetails() . PHP_EOL;
