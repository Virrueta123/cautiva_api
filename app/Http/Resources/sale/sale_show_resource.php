<?php

namespace App\Http\Resources\sale;

use App\Http\Resources\box\box_sale_resource;
use App\Http\Resources\client\client_sale_resource;
use App\Http\Resources\client\client_select_resource;
use App\Http\Resources\dt_sale\dt_sale_resource;
use App\Http\Resources\dt_sales_payments\dt_sales_payments_resource;
use App\Http\Resources\user\user_resource;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class sale_show_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->sale_id),
            'client' => client_sale_resource::make($this->client),
            'box' => box_sale_resource::make($this->box),
            'user' => user_resource::make($this->user),
            'dt_sale' => dt_sale_resource::collection($this->dt_sale),
            'dt_sales_payments' => dt_sales_payments_resource::collection($this->dt_sales_payments),
            'tipo_documento' => $this->tipo_documento,
            'serie' => $this->serie,
            'correlativo' => $this->correlativo,
            'setDni' => $this->setDni,
            'setNombre' => $this->setNombre,
            'setApellido' => $this->setApellido,
            "setRazonSocial" => $this->setRazonSocial,
            "setRuc" => $this->setRuc,
            'created_at' => $this->created_at,
            'setMtoOperGravadas' => $this->setMtoOperGravadas,
            'setMtoIGV' => $this->setMtoIGV,
            'setTotalImpuestos' => $this->setTotalImpuestos,
            'setValorVenta' => $this->setValorVenta,
            'setSubTotal' => $this->setSubTotal,
            'setMtoImpVenta' => $this->setMtoImpVenta,
            'setMtoOperExoneradas' => $this->setMtoOperExoneradas,
            'setMtoOperInafectas' => $this->setMtoOperInafectas,
            'setMtoOtrosCargos' => $this->setMtoOtrosCargos,
            'estado' => $this->estado,
            'fecha_baja' => $this->fecha_baja,
            'descuento' => $this->descuento,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'observations' => $this->observations,
            'message_error' => $this->message_error,
            'fecha_baja' => $this->fecha_baja,
            'fecha_baja' => $this->fecha_baja,
            'codigo_error' => $this->codigo_error,
        ];
    }
}
