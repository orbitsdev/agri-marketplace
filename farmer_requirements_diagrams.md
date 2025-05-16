# Farmer Requirements System Diagrams

This document contains various diagrams illustrating the architecture and data relationships of the Farmer Requirements system within the AgriMarket e-commerce platform.

## Table of Contents
- [Conceptual Diagram](#conceptual-diagram)
- [Entity-Relationship Diagram](#entity-relationship-diagram)
- [Physical Data Model](#physical-data-model)
- [System Flow Diagram](#system-flow-diagram)
- [Component Diagram](#component-diagram)
- [Sequence Diagrams](#sequence-diagrams)
- [Database Table Visualizations](#database-table-visualizations)

## Conceptual Diagram

```mermaid
graph TD
    User[User] --> |registers as| Farmer[Farmer]
    Admin[Administrator] --> |defines| Requirement[Requirements]
    Requirement --> |assigned to| Farmer
    Farmer --> |uploads documents for| FarmerRequirement[Farmer Requirements]
    FarmerRequirement --> |references| Requirement
    Admin --> |reviews| FarmerRequirement
    Admin --> |approves/rejects| FarmerRequirement
    FarmerRequirement --> |stores| Document[Documents]
    Document --> |attached to| FarmerRequirement
    FarmerObserver[Farmer Observer] --> |automatically assigns| Requirement
    Command[Command Line Tool] --> |adds new requirements to| Farmer
```

## Entity-Relationship Diagram

```mermaid
erDiagram
    USERS {
        id int PK
        name string
        email string
        password string
        role enum
    }
    
    FARMERS {
        id int PK
        user_id int FK
        farm_name string
        location text
        farm_size string
        description text
        contact string
        status enum
        remarks text
        draft_status boolean
    }
    
    REQUIREMENTS {
        id int PK
        name string
        description text
        is_active boolean
        is_mandatory boolean
        order_index int
    }
    
    FARMER_REQUIREMENTS {
        id int PK
        farmer_id int FK
        requirement_id int FK
        status enum
        is_checked boolean
        remarks text
    }
    
    MEDIA {
        id int PK
        model_type string
        model_id int
        collection_name string
        file_name string
        mime_type string
        size int
    }
    
    USERS ||--o{ FARMERS : "has"
    FARMERS ||--o{ FARMER_REQUIREMENTS : "must fulfill"
    REQUIREMENTS ||--o{ FARMER_REQUIREMENTS : "assigned to"
    FARMER_REQUIREMENTS ||--o{ MEDIA : "has"
```

## Physical Data Model

```mermaid
classDiagram
    class users {
        +id: bigint(20) PK
        +name: varchar(255)
        +email: varchar(255)
        +email_verified_at: timestamp
        +password: varchar(255)
        +remember_token: varchar(100)
        +created_at: timestamp
        +updated_at: timestamp
        +otp: varchar(255)
        +otp_expires_at: timestamp
    }
    
    class farmers {
        +id: bigint(20) PK
        +user_id: bigint(20) FK
        +farm_name: varchar(255)
        +location: text
        +farm_size: varchar(255)
        +description: text
        +contact: varchar(255)
        +status: enum
        +remarks: text
        +created_at: timestamp
        +updated_at: timestamp
        +draft_status: boolean
    }
    
    class requirements {
        +id: bigint(20) PK
        +name: varchar(255)
        +description: text
        +is_active: boolean
        +is_mandatory: boolean
        +order_index: int
        +created_at: timestamp
        +updated_at: timestamp
    }
    
    class farmer_requirements {
        +id: bigint(20) PK
        +farmer_id: bigint(20) FK
        +requirement_id: bigint(20) FK
        +status: enum
        +is_checked: boolean
        +remarks: text
        +created_at: timestamp
        +updated_at: timestamp
    }
    
    class media {
        +id: bigint(20) PK
        +model_type: varchar(255)
        +model_id: bigint(20)
        +uuid: varchar(36)
        +collection_name: varchar(255)
        +name: varchar(255)
        +file_name: varchar(255)
        +mime_type: varchar(255)
        +disk: varchar(255)
        +size: bigint(20)
        +manipulations: json
        +custom_properties: json
        +generated_conversions: json
        +responsive_images: json
        +order_column: int
        +created_at: timestamp
        +updated_at: timestamp
    }
    
    users "1" --> "0..1" farmers
    farmers "1" --> "*" farmer_requirements
    requirements "1" --> "*" farmer_requirements
    farmer_requirements "1" --> "0..1" media
```

## System Flow Diagram

```mermaid
graph TD
    Start((Start)) --> UserRegistration[User Registration]
    UserRegistration --> FarmerRegistration[Farmer Registration]
    FarmerRegistration --> FarmerObserver[Farmer Observer Triggered]
    FarmerObserver --> AssignRequirements[Assign Active Requirements]
    AssignRequirements --> NotifyFarmer[Notify Farmer]
    NotifyFarmer --> FarmerDashboard[Farmer Dashboard]
    
    FarmerDashboard --> ViewRequirements[View Requirements]
    ViewRequirements --> UploadDocument[Upload Document]
    UploadDocument --> SubmitRequirement[Submit Requirement]
    SubmitRequirement --> AdminReview[Admin Reviews Requirement]
    
    AdminReview --> Decision{Decision?}
    Decision -->|Approved| MarkApproved[Mark as Approved]
    Decision -->|Rejected| MarkRejected[Mark as Rejected]
    
    MarkApproved --> CheckAllRequirements[Check All Requirements]
    MarkRejected --> NotifyRejection[Notify Rejection]
    NotifyRejection --> ReviseDocument[Revise Document]
    ReviseDocument --> UploadDocument
    
    CheckAllRequirements --> AllMandatoryApproved{All Mandatory Approved?}
    AllMandatoryApproved -->|Yes| ApproveFarmer[Approve Farmer]
    AllMandatoryApproved -->|No| WaitForCompletion[Wait for Completion]
    
    AdminPanel[Admin Panel] --> AddNewRequirement[Add New Requirement]
    AddNewRequirement --> RunCommand[Run Command]
    RunCommand --> AssignToExistingFarmers[Assign to Existing Farmers]
    AssignToExistingFarmers --> NotifyFarmers[Notify Farmers]
```

## Component Diagram

```mermaid
graph TB
    subgraph "User Management"
        Authentication[Authentication]
        UserRegistration[User Registration]
        FarmerRegistration[Farmer Registration]
    end
    
    subgraph "Requirement System"
        RequirementDefinition[Requirement Definition]
        RequirementAssignment[Requirement Assignment]
        DocumentUpload[Document Upload]
        RequirementReview[Requirement Review]
        FarmerObserver[Farmer Observer]
        CommandLineTools[Command Line Tools]
    end
    
    subgraph "Admin Dashboard"
        RequirementResource[Requirement Resource]
        FarmerResource[Farmer Resource]
        FarmerRequirementManagement[Farmer Requirement Management]
    end
    
    subgraph "Notification System"
        DatabaseNotifications[Database Notifications]
        EmailNotifications[Email Notifications]
    end
    
    subgraph "File Storage"
        MediaLibrary[Spatie Media Library]
        FileStorage[File Storage]
    end
    
    Authentication --> UserRegistration
    UserRegistration --> FarmerRegistration
    FarmerRegistration --> FarmerObserver
    
    FarmerObserver --> RequirementAssignment
    RequirementDefinition --> RequirementAssignment
    RequirementAssignment --> DatabaseNotifications
    
    DocumentUpload --> MediaLibrary
    MediaLibrary --> FileStorage
    
    RequirementResource --> RequirementDefinition
    FarmerResource --> FarmerRequirementManagement
    FarmerRequirementManagement --> RequirementReview
    
    CommandLineTools --> RequirementAssignment
    RequirementReview --> DatabaseNotifications
    DatabaseNotifications --> EmailNotifications
```

## Sequence Diagrams

### Farmer Registration and Requirement Assignment

```mermaid
sequenceDiagram
    actor User
    participant Auth as Authentication
    participant FarmerReg as Farmer Registration
    participant FarmerObs as Farmer Observer
    participant ReqSystem as Requirement System
    participant DB as Database
    participant Notif as Notification System
    
    User->>Auth: Register Account
    Auth->>DB: Create User
    DB-->>Auth: User Created
    User->>FarmerReg: Register as Farmer
    FarmerReg->>DB: Create Farmer
    DB-->>FarmerReg: Farmer Created
    FarmerReg->>FarmerObs: Trigger Observer
    FarmerObs->>ReqSystem: Get Active Requirements
    ReqSystem->>DB: Query Requirements
    DB-->>ReqSystem: Active Requirements
    FarmerObs->>DB: Create Farmer Requirements
    DB-->>FarmerObs: Requirements Assigned
    FarmerObs->>Notif: Notify Farmer
    Notif-->>User: Requirements Notification
```

### Document Upload and Review Process

```mermaid
sequenceDiagram
    actor Farmer
    actor Admin
    participant UI as Farmer UI
    participant ReqSystem as Requirement System
    participant MediaLib as Media Library
    participant DB as Database
    participant Notif as Notification System
    
    Farmer->>UI: View Requirements
    UI->>ReqSystem: Get Farmer Requirements
    ReqSystem->>DB: Query Requirements
    DB-->>ReqSystem: Requirements List
    ReqSystem-->>UI: Display Requirements
    Farmer->>UI: Upload Document
    UI->>MediaLib: Store Document
    MediaLib->>DB: Save Media Record
    UI->>ReqSystem: Update Requirement Status
    ReqSystem->>DB: Set Status to Submitted
    
    Admin->>ReqSystem: Review Requirements
    ReqSystem->>DB: Get Submitted Requirements
    DB-->>ReqSystem: Submitted Requirements
    Admin->>ReqSystem: Approve/Reject Requirement
    ReqSystem->>DB: Update Requirement Status
    ReqSystem->>Notif: Notify Farmer
    Notif-->>Farmer: Status Update Notification
```

### Adding New Requirement to Existing Farmers

```mermaid
sequenceDiagram
    actor Admin
    participant UI as Admin UI
    participant CMD as Command Line
    participant ReqSystem as Requirement System
    participant DB as Database
    participant Notif as Notification System
    
    Admin->>UI: Create New Requirement
    UI->>DB: Save Requirement
    Admin->>CMD: Run Add Requirement Command
    CMD->>ReqSystem: Get Existing Farmers
    ReqSystem->>DB: Query Farmers
    DB-->>ReqSystem: Farmers List
    CMD->>ReqSystem: Assign Requirement to Farmers
    ReqSystem->>DB: Create Farmer Requirements
    CMD->>Notif: Notify Farmers (Optional)
    Notif-->>Farmers: New Requirement Notification
```

## Database Table Visualizations

### Requirements Table

| Column Name | Data Type | Constraints | Description |
|------------|-----------|-------------|-------------|
| id | bigint(20) | PRIMARY KEY | Unique identifier for requirements |
| name | varchar(255) | NOT NULL | Name of the requirement |
| description | text | NULL | Description of the requirement |
| is_active | boolean | DEFAULT true | Whether requirement is active |
| is_mandatory | boolean | DEFAULT true | Whether requirement is mandatory |
| order_index | int | DEFAULT 0 | Display order |
| created_at | timestamp | NULL | Record creation timestamp |
| updated_at | timestamp | NULL | Record update timestamp |

### Farmer Requirements Table

| Column Name | Data Type | Constraints | Description |
|------------|-----------|-------------|-------------|
| id | bigint(20) | PRIMARY KEY | Unique identifier for farmer requirements |
| farmer_id | bigint(20) | FOREIGN KEY | Reference to farmers table |
| requirement_id | bigint(20) | FOREIGN KEY | Reference to requirements table |
| status | enum | DEFAULT 'Pending' | Status of requirement (Pending, Submitted, Approved, Rejected) |
| is_checked | boolean | DEFAULT false | Whether requirement has been reviewed |
| remarks | text | NULL | Additional notes or feedback |
| created_at | timestamp | NULL | Record creation timestamp |
| updated_at | timestamp | NULL | Record update timestamp |

### Database Relationship Visualization

```
+---------------+     +---------------+     +---------------+
|    Users      |     |    Farmers    |     | Requirements  |
+---------------+     +---------------+     +---------------+
| id            |<--->| id            |     | id            |
| name          |     | user_id       |     | name          |
| email         |     | farm_name     |     | description   |
| password      |     | location      |     | is_active     |
|               |     | farm_size     |     | is_mandatory  |
|               |     | description   |     | order_index   |
|               |     | contact       |     |               |
|               |     | status        |     |               |
|               |     | remarks       |     |               |
+---------------+     +---------------+     +---------------+
                             ^                    ^
                             |                    |
                             v                    v
                      +---------------+     +---------------+
                      | Farmer_Reqs   |     |    Media      |
                      +---------------+     +---------------+
                      | id            |<--->| id            |
                      | farmer_id     |     | model_type    |
                      | requirement_id|     | model_id      |
                      | status        |     | collection_name|
                      | is_checked    |     | file_name     |
                      | remarks       |     | mime_type     |
                      +---------------+     | size          |
                                            +---------------+
```

These diagrams provide a comprehensive visualization of the Farmer Requirements system, showing the relationships between entities, the system architecture, and process flows. The diagrams use the Mermaid syntax for visualization and can be rendered in any Markdown viewer that supports Mermaid diagrams.
