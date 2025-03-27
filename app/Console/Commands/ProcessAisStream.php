<?php

namespace App\Console\Commands;

use App\Models\AisData;
use App\Models\AisStatic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ProcessAisStream extends Command
{
    protected $signature = 'app:process-stream';
    protected $description = 'Process AIS data from Redis Stream';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting AIS stream processor...");

        while (true) {
            try {
                // Baca dari stream dengan consumer group
                $messages = Redis::xreadgroup(
                    'ais_group',
                    'ais_consumer',
                    ['ais_stream' => '>'],
                    1,
                    1000
                );

                if (!empty($messages)) {
                    foreach ($messages[0][1] as $message) {
                        $dataArray = json_decode($message[1]['payload'], true);
                        $this->processBatch($dataArray);

                        // Acknowledge message
                        Redis::xack('ais_stream', 'ais_group', [$message[0]]);
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error processing message: " . $e->getMessage());
                sleep(1);
            }
        }
    }

    private function processBatch(array $dataArray)
    {
        $aisDataList = [];
        $aisStaticList = [];

        foreach ($dataArray as $data) {
            if (!isset($data['mmsi'])) continue;

            // Data dinamis untuk tabel AisData
            $aisDataList[] = [
                'msg_type' => $data['id'] ?? null,
                'repeat_indicator' => $data['repeat_indicator'] ?? null,
                'mmsi' => $data['mmsi'],
                'nav_status' => $data['nav_status'] ?? null,
                'rot_over_range' => $data['rot_over_range'] ?? null,
                'rot' => $data['rot'] ?? null,
                'sog' => $data['sog'] ?? null,
                'position_accuracy' => $data['position_accuracy'] ?? null,
                'x' => $data['x'] ?? null,
                'y' => $data['y'] ?? null,
                'cog' => $data['cog'] ?? null,
                'true_heading' => $data['true_heading'] ?? null,
                'timestamp' => $data['timestamp'] ?? null,
                'special_manoeuvre' => $data['special_manoeuvre'] ?? null,
                'raim' => $data['raim'] ?? null,
                'sync_state' => $data['sync_state'] ?? null,
                'slot_increment' => $data['slot_increment'] ?? null,
                'slots_to_allocate' => $data['slots_to_allocate'] ?? null,
                'keep_flag' => $data['keep_flag'] ?? null,
                'altitude' => $data['altitude'] ?? null,
                'channel' => $data['channel'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Data statis untuk tabel AisStatic (jika ada)
            if ($data['id'] === 5 || $data['id'] === 19 || $data['id'] === 21 || $data['id'] === 24) {
                $aisStaticList[$data['mmsi']] = [
                    'msg_type' => $data['id'] ?? null,
                    'repeat_indicator' => $data['repeat_indicator'] ?? null,
                    'mmsi' => $data['mmsi'],
                    'message_id' => $data['message_id'] ?? null,
                    'fragment_count' => $data['fragment_count'] ?? null,
                    'ais_version' => $data['ais_version'] ?? null,
                    'imo_number' => $data['imo_num'] ?? null,
                    'call_sign' => $data['callsign'] ?? null,
                    'ship_name' => $data['name'] ?? null,
                    'ship_type' => $data['type_and_cargo'] ?? null,
                    'vendor_id' => $data['vendor_id'] ?? null,
                    'dimension_to_bow' => $data['dim_a'] ?? null, // Mapping 'dim_a' ke 'dimension_to_bow'
                    'dimension_to_stern' => $data['dim_b'] ?? null, // Mapping 'dim_b' ke 'dimension_to_stern'
                    'dimension_to_port' => $data['dim_c'] ?? null, // Mapping 'dim_c' ke 'dimension_to_port'
                    'dimension_to_starboard' => $data['dim_d'] ?? null, // Mapping 'dim_d' ke 'dimension_to_starboard'
                    'fix_type' => $data['fix_type'] ?? null,
                    'eta_month' => $data['eta_month'] ?? null,
                    'eta_day' => $data['eta_day'] ?? null,
                    'eta_hour' => $data['eta_hour'] ?? null,
                    'eta_minute' => $data['eta_minute'] ?? null,
                    'draught' => $data['draught'] ?? null,
                    'destination' => isset($data['destination']) ? trim(str_replace('@', '', $data['destination'])) : null,
                    'dte' => $data['dte'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert ke AisData jika ada data
        if (!empty($aisDataList)) {
            AisData::insert($aisDataList);
        }

        // Update atau insert data ke AisStatic
        foreach ($aisStaticList as $mmsi => $staticData) {
            AisStatic::updateOrCreate(['mmsi' => $mmsi], $staticData);
        }

        $this->info("Processed batch: " . count($aisDataList) . " items");
    }
}
