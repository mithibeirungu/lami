# Lami

# Tables

## Users

| Field | Type | Description |
| --- | --- | --- |
| user_id | bigserial PK | Unique |
| username | varchar(50) |  |
| email | varchar(100) |  |
| full_name | varchar(100) |  |
| password_hash | text |  |
| role | enum(‘overseer’, ‘motor_scribe’, ‘driver’) |  |
| avatar_url | text |  |
| created_at | timestamp |  |
| updated_at | timestamp |  |

## cars

| Field | Type |
| --- | --- |
| car_id | bigserial PK |
| title | varchar(150) |
| brand_id | FK |
| model | varchar(100) |
| year | integer |
| body_type_id | FK |
| transmission | varchar(50) |
| fuel_type | varchar(50) |
| engine_type | varcahr(50) |
| engine_size | varchar(20) |
| horsepower | integer |
| torque | integer |
| seating_capacity | integer |
| drive_type | varchar(50) |
| description | text |
| thumbnail_image | text |
| created_by | FK users.user_id |
| created_at | timestamp |
| updated_at | timestamp |

## brands

| Field | Type |
| --- | --- |
| brand_id | bigserial PK |
| name | varchar(100) |
| country | varchar(100) |
| logo_url | text |

## body_types

| Field | Type |
| --- | --- |
| body_type_id | bigserial PK |
| name | varchar(50) |

## image_categories

| Field | Type |
| --- | --- |
| category_id | bigserial PK |
| name | varchar(50) |
| description | text |
|  |  |

## car_images

| Field | Type |
| --- | --- |
| image_id | bigserial PK |
| car_id | FK |
| category_id | FK |
| image_url | text |
| title | varchar(100) |
| is_primary | boolean |
| created_at | timestamp |

## comments

| Field | Type |
| --- | --- |
| comment_id | bigserial PK |
| car_id | FK |
| user_id | FK |
| content | text |
| created_at | timestamp |

## favorites

| Field | Type |
| --- | --- |
| favorite_id | bigserial PK |
| user_id | FK |
| car_id | FK |
| created_at | timestamp |