<?php

class Employee {
    private Company $mainJob;
    private ?Company $secondJob;

    public function __construct(Company $mainJob, ?Company $secondJob = null) {
        $this->mainJob = $mainJob;
        $this->secondJob = $secondJob;
    }
}

class Company {
    private array $employees = [];
    private array $boardMembers = [];
    private ?Company $parentCompany = null;
    private array $subsidiaries = [];

    public function addEmployee(Employee $employee): void {
        $this->employees[] = $employee;
    }

    public function setBoardMember(BoardMember $boardMember, int $position): void {
        if ($position >= 0 && $position < 10) {
            $this->boardMembers[$position] = $boardMember;
        }
    }

    // 親会社の設定（0または1つ）
    public function setParentCompany(?Company $company): void {
        $this->parentCompany = $company;
    }

    // 子会社の追加（複数可）
    public function addSubsidiary(Company $company): void {
        if (!in_array($company, $this->subsidiaries, true)) {
            $this->subsidiaries[] = $company;
            // 子会社側にも親会社を設定することで関連性を双方向で保つ
            $company->setParentCompany($this);
        }
    }

    // 循環参照を許可（子会社が自分自身を子会社に持つことも可能）
    public function addSelfAsSubsidiary(): void {
        $this->addSubsidiary($this);
    }
}

class BoardMember {
    private array $companiesManaging = [];

    public function setCompany(Company $company, int $position): void {
        if ($position >= 0 && $position < 5) {
            $this->companiesManaging[$position] = $company;
        }
    }
}

// メイン処理で要件をシミュレーション
$company1 = new Company();
$company2 = new Company();
$company3 = new Company();

// 従業員が複数の会社に勤務可能
$employee = new Employee($company1, $company2);
$company1->addEmployee($employee);
$company2->addEmployee($employee);

// 役員が複数会社の管理に関与可能
$boardMember = new BoardMember();
$company1->setBoardMember($boardMember, 0);
$boardMember->setCompany($company2, 0);

// 子会社と親会社の関係を設定
$company1->addSubsidiary($company2); // 会社1 → 会社2
$company2->addSubsidiary($company3); // 会社2 → 会社3（孫会社の例）

// 循環参照の例（会社が自社を子会社として設定可能）
$company3->addSelfAsSubsidiary();

// これにより以下の関係が実現:
// company1 → company2 → company3 → company3 (自社を含む再帰構造)
