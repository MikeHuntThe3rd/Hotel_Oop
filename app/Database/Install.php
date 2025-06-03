<?php

namespace App\Database;

use App\Models\RoomModel;
use App\Models\GuestModel;
use App\Views\Display;
use App\Database\Database;
use Exception;

class Install extends Database
{

    protected CONST SETUP = [
    'numberOfRecords' => 20,
    'floorCount' => 5,
    'roomCount' => 10,
    'days' => 5,
    'accommodation' => [2, 4, 6],
    'priceRange' => [10000, 40000],
    'lastNames' => [
        'Major','Riz','Kard','Pum','Víz','Kandisz','Patta','Para','Pop','Remek','Ének','Szalmon','Ultra','Dil','Git','Har','Külö','Harm',
        'Zsíros B.','Virra','Kasza','Budipa','Bekre','Fejet','Minden','Bármi','Lapos','Bor','Mikorka','Szikla','Fekete','Rabsz','Kalim',
        'Békés','Szenyo'
    ],
    'firstNames' => [
        'Ottó','Pál','Elek','Simon','Ödön','Kálmán','Áron','Elemér','Szilárd','Csaba','Anna','Virág','Nóra','Zita','Ella','Viola','Emma',
        'Mónika','Dóra','Blanka','Piroska','Lenke','Mercédesz','Olga','Rita',
    ],
    'comments' => [
        'Szép!',
        'Oké',
        'Remek',
        'Ügyi',
        'Király!',
        'Hűha',
        'Menő',
        'Váó',
        'Zsír',
        'Tuti'
        ]
    ];


    public function dbExists(): bool
    {
        try {
            $mysqli = $this->getConn('mysql');
            if (!$mysqli) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", self::DEFAULT_CONFIG['database']);
            $result = $mysqli->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $mysqli->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());

            return false;
        }
        finally {
            // Ensure the database connection is always closed
            $mysqli?->close();
        }

    }

    public function getConn($dbName)
    {
        try {
            // Kapcsolódás az adatbázishoz
            $mysqli = mysqli_connect(self::DEFAULT_CONFIG["host"], self::DEFAULT_CONFIG["user"], self::DEFAULT_CONFIG["password"], $dbName);
    
            // Ellenőrizzük a csatlakozás sikerességét
            if (!$mysqli) {
                throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
            }
    
            return $mysqli;
        } catch (Exception $e) {
            // Hibaüzenet megjelenítése a felhasználónak
            echo $e->getMessage();
    
            // Hibanaplózás
            error_log($e->getMessage());
    
            // Hibás csatlakozás esetén `null`-t ad vissza
            return null;
        }
    }
    public function create_db(){
        $myfile = fopen("hotel.sql", "r");
        $txt = fread($myfile,filesize("hotel.sql"));
        fclose($myfile);
        $conn = $this->getConn('');
        $conn->multi_query($txt);
        $conn->close();
    }

    public function fill_tables($db_name = self::DEFAULT_CONFIG['database']): void
    {
        $max_rooms = self::SETUP['floorCount'] * self::SETUP['roomCount'];
        $record_count = min(self::SETUP['numberOfRecords'], $max_rooms);

        $this->fill_guests_table($db_name, $record_count);
        $this->fill_rooms_table($db_name, $record_count);
        $this->fill_reservations_table($db_name, $record_count);
    }

    private function fill_guests_table(string $db_name, int $count): bool
    {
        try {
            $sql = "INSERT INTO `$db_name`.guests(name, age) VALUES ";
            $values = [];

            for ($i = 0; $i < $count; $i++) {
                $last = self::SETUP['lastNames'][array_rand(self::SETUP['lastNames'])];
                $first = self::SETUP['firstNames'][array_rand(self::SETUP['firstNames'])];
                $age = rand(18, 100);
                $values[] = sprintf("('%s %s', %d)", $last, $first, $age);
            }

            $sql .= implode(',', $values) . ';';

            $conn = $this->getConn('hotel');
            $conn->query($sql);
            $conn->close();

            return true;
        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());
            return false;
        }
    }

    private function fill_rooms_table(string $db_name, int $count): bool
    {
        try {
            $sql = "INSERT INTO `$db_name`.rooms(floor, room_number, accommodation, price, comment) VALUES ";
            $values = [];

            $available_rooms = [];
            for ($floor = 0; $floor < self::SETUP['floorCount']; $floor++) {
                for ($room = 1; $room <= self::SETUP['roomCount']; $room++) {
                    $available_rooms[] = [$floor, $room];
                }
            }

            shuffle($available_rooms);
            $available_rooms = array_slice($available_rooms, 0, $count);

            foreach ($available_rooms as [$floor, $room_number]) {
                $room_id = $room_number + $floor * self::SETUP['roomCount'];
                $accommodation = self::SETUP['accommodation'][array_rand(self::SETUP['accommodation'])];
                $price = round(rand(self::SETUP['priceRange'][0], self::SETUP['priceRange'][1]), -2);
                $comment = self::SETUP['comments'][array_rand(self::SETUP['comments'])];

                $values[] = "($floor, $room_id, $accommodation, $price, '$comment')";
            }

            $sql .= implode(',', $values) . ';';

            $conn = $this->getConn('hotel');
            $conn->query($sql);
            $conn->close();

            return true;
        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());
            return false;
        }
    }

    private function fill_reservations_table(string $db_name, int $count): bool
    {
        try {
            $sql = "INSERT INTO `$db_name`.reservations(room_id, guest_id, days, date) VALUES ";
            $values = [];

            $room_model = new RoomModel();
            $guest_model = new GuestModel();

            $rooms = $room_model->all(['order_by' => ['RAND()'], 'direction' => ['ASC']]);
            $guests = $guest_model->all(['order_by' => ['RAND()'], 'direction' => ['ASC']]);

            $pairs = min(count($rooms), count($guests), $count);

            for ($i = 0; $i < $pairs; $i++) {
                $room_id = $rooms[$i]->id;
                $guest_id = $guests[$i]->id;
                $days = rand(1, self::SETUP['days']);
                $date = date("Y-m-d", rand(strtotime("2015-01-01"), strtotime("2016-11-01")));

                $values[] = "($room_id, '$guest_id', $days, '$date')";
            }

            $sql .= implode(',', $values) . ';';

            $conn = $this->getConn('hotel');
            $conn->query($sql);
            $conn->close();

            return true;
        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());
            return false;
        }
    }
}