# PHP Company Hierarchy

PHPで実装した企業の階層管理プログラム。企業の親子関係、従業員の所属、役員の管理などの操作が可能。

## 詳細説明



| メソッド | 説明 |
|----------|------|
| __construct(Company $mainJob, ?Company $secondJob = null) | Employeeクラスのコンストラクタ。従業員の主な勤務先と副業先（任意）を設定します。 |
| addEmployee(Employee $employee): void | 会社に従業員を追加します。 |
| setBoardMember(BoardMember $boardMember, int $position): void | 指定の位置に役員を設定します（最大10名）。 |
| setParentCompany(?Company $company): void | 会社の親会社を設定します（0または1つ）。 |
| addSubsidiary(Company $company): void | 会社の子会社を追加します（複数可）。追加時に子会社側にも親会社が設定されます。 |
| addSelfAsSubsidiary(): void | 会社が自社を子会社として設定する（循環参照の可能性あり）。 |
| setCompany(Company $company, int $position): void | 役員が管理する企業を設定します（最大5社）。 |

### 機能
- 企業の **親子関係** を管理
- 従業員の **所属会社の設定**（複数企業に所属可能）
- 役員の **管理企業の設定**（複数企業を管理可能）
- **循環参照** のサポート（会社が自社を子会社に持つことが可能）

