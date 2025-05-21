<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupStripeTestKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:setup-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura claves de Stripe para pruebas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Configurando claves de prueba para Stripe...');
        
        // Claves de prueba de Stripe (seguras para compartir, son de prueba)
        $stripePublicKey = 'pk_test_51AbC123DefGhIjKlMnOpQrStUvWxYz';
        $stripeSecretKey = 'sk_test_51AbC123DefGhIjKlMnOpQrStUvWxYz';
        
        // Leer el archivo .env
        $envFile = file_get_contents(base_path('.env'));
        
        // Reemplazar o agregar las claves de Stripe
        if (strpos($envFile, 'STRIPE_KEY=') !== false) {
            $envFile = preg_replace('/STRIPE_KEY=.*/', 'STRIPE_KEY=' . $stripePublicKey, $envFile);
        } else {
            $envFile .= "\nSTRIPE_KEY=" . $stripePublicKey;
        }
        
        if (strpos($envFile, 'STRIPE_SECRET=') !== false) {
            $envFile = preg_replace('/STRIPE_SECRET=.*/', 'STRIPE_SECRET=' . $stripeSecretKey, $envFile);
        } else {
            $envFile .= "\nSTRIPE_SECRET=" . $stripeSecretKey;
        }
        
        // Guardar el archivo .env actualizado
        file_put_contents(base_path('.env'), $envFile);
        
        $this->info('Claves de prueba de Stripe configuradas correctamente.');
        $this->info('NOTA: Estas son claves de prueba y solo funcionan con tarjetas de prueba de Stripe.');
        $this->info('Para probar pagos, puedes usar la tarjeta de prueba: 4242 4242 4242 4242');
        $this->info('Fecha de vencimiento: cualquier fecha futura');
        $this->info('CVC: cualquier número de 3 dígitos');
    }
}
