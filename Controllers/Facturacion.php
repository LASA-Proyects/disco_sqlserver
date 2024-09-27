<?php
require 'C:/xampp/htdocs/disco2023/guia_electronica/Numletras.php';
require 'C:/xampp/htdocs/disco2023/guia_electronica/Variables_diversas_model.php';
require 'C:/xampp/htdocs/disco2023/guia_electronica/efactura.php';
class Facturacion extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    
    public function guias_electronicas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        /*$verificar = $this->model->verificarPermiso($id_usuario, 1, 1);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['tipo_usuarios'] = $this->model->getTipoUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }*/
        $data['departamentos'] = $this->model->getDatosPais("ubigeo_departamentos");
        $data['provincias'] = $this->model->getDatosPais("ubigeo_provincias");
        $data['distritos'] = $this->model->getDatosPais("ubigeo_distritos");
        $data['destinatarios'] = $this->model->getDatosGenerales('contactos');
        $data['motivos'] = $this->model->getDatosGenerales('guia_motivo_traslados');
        $data['modalidades'] = $this->model->getDatosGenerales('guia_modalidad_traslados');
        $this->views->getView($this, "guias_electronicas", $data);
    }

    public function monitor_guias_electronicas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        /*$verificar = $this->model->verificarPermiso($id_usuario, 1, 1);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['tipo_usuarios'] = $this->model->getTipoUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }*/
        $this->views->getView($this, "monitor_guias_electronicas");
    }

    public function buscarProvincia($cod_provincia)
    {
        $data = $this->model->buscarProvincia($cod_provincia);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarDistrito($cod_distrito)
    {
        $data = $this->model->buscarDistrito($cod_distrito);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarProductos()
    {
        $data = $this->model->buscarProductos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarProductosCod($id)
    {
        $data = $this->model->getProdCod($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function listarProductsGuia()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        foreach ($json as $productos){
            $result = $this->model->getProdCod($productos['cod_producto']);
            $data['id'] = $result['id'];
            $data['codigo'] = $result['codigo'];
            $data['nombre'] = $result['descripcion'];
            $data['unidad'] = $result['unidad_med'];
            $data['precio'] = $result['precio_venta'];
            $data['cantidad'] = $productos['cantidad'];
            $sub_total = $data['precio']*$productos['cantidad'];
            $data['sub_total'] = number_format($sub_total, 2);
            $total += $sub_total;
            array_push($array['productos'],$data);
        }
        $array['total'] = number_format($total, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarGuia()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $data_guia = $data['datos_guia'];
        $data_products_guia = $data['listaProductsGuia'];
        $data_destinatario = $this->model->dataDestinatario($data_guia['destinatario']);
        $path = "guia_electronica/";
        $guia = $this->model->getGuiaCorrl($_SESSION['id_almacen'], 'G');
        $empresa = $this->model->getEmpresa();
        $nombre_archivo = $empresa['ruc'].'-09-'.$guia['serie'].'-'.$guia['correlativo'];
        if(file_exists($path."XML/".$nombre_archivo.".xml")){
            unlink($path."XML/".$nombre_archivo.".xml");
        }

        $this->crear_xml($path, $nombre_archivo, $empresa, $guia, $data_guia, $data_products_guia, $data_destinatario);
        $this->firmar_xml($path, $nombre_archivo, "produccion");
        $this->solicitar_token($path, $nombre_archivo, $empresa);
    }

    public function listarGuiasElectronicas()
    {
        $data = $this->model->listarGuiasElectronicas();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['respuesta_sunat_codigo'] === 0) {
                if (empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_razon_social'];
                } elseif (!empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_apellidoPaterno']. $data[$i]['destinatario_apellidoMaterno']. $data[$i]['destinatario_nombres'];
                } else {
                    $data[$i]['destinatario'] = '';
                }
                $data[$i]['ticket'] = '<button class="btn btn- btn-sm" type="button" onclick="btnVerTicketGuia(' . $data[$i]['id'] . ');"><i class="fas fa-ticket-alt"></i></button>';
                $data[$i]['respuesta_sunat_codigo'] = '<span class="badge badge-success">Aceptado</span>';
                $data[$i]['acciones'] = '';
            }else if($data[$i]['respuesta_sunat_codigo'] != 0){
                if (empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_razon_social'];
                } elseif (!empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_apellidoPaterno']. $data[$i]['destinatario_apellidoMaterno']. $data[$i]['destinatario_nombres'];
                } else {
                    $data[$i]['destinatario'] = '';
                }
                $data[$i]['ticket'] = '<button class="btn btn- btn-sm" type="button" onclick="btnVerTicketGuia(' . $data[$i]['id'] . ');"><i class="fas fa-ticket-alt"></i></button>';
                $data[$i]['xml'] = '<button class="btn btn- btn-sm" type="button" onclick="btnVerXml(\''.$data[$i]['empresa_ruc'].'-09-'.$data[$i]['serie'].'-'.$data[$i]['numero'].'\');"><i class="fas fa-file-code"></i></button>';
                $data[$i]['respuesta_sunat_codigo'] = '<span class="badge badge-danger">Rechazado</span>';
                $data[$i]['acciones'] = '';
            }else if($data[$i]['respuesta_sunat_codigo'] == NULL){
                if (empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_razon_social'];
                } elseif (!empty($data[$i]['destinatario_num_doc']) && !empty($data[$i]['destinatario_ruc'])) {
                    $data[$i]['destinatario'] = $data[$i]['destinatario_apellidoPaterno']. $data[$i]['destinatario_apellidoMaterno']. $data[$i]['destinatario_nombres'];
                } else {
                    $data[$i]['destinatario'] = '';
                }
                $data[$i]['ticket'] = '<button class="btn btn- btn-sm" type="button" onclick="btnVerTicketGuia(' . $data[$i]['id'] . ');"><i class="fas fa-ticket-alt"></i></button>';
                $data[$i]['xml'] = '<button class="btn btn- btn-sm" type="button" onclick="btnVerXml(' . $data[$i]['id'] . ');"><i class="fas fa-file-code"></i></button>';
                $data[$i]['respuesta_sunat_codigo'] = '<span class="badge badge-info">Creado</span>';
                $data[$i]['acciones'] = '<button class="btn btn-primary btn-sm" type="button" onclick="btnProcesarSunat(\''.$data[$i]['empresa_ruc'].'-09-'.$data[$i]['serie'].'-'.$data[$i]['numero'].'\');"><i class="fas fa-paper-plane"></i></button>';
            } 
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultaTipoProceso()
    {
        $data = $this->model->consultaTipoProceso();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultarTicketGuia($id)
    {
        $data = $this->model->consultarTicketGuia($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die(); 
    }

    public function procesarSunat($nombre_archivo)
    {
        $path = "guia_electronica/";
        $contenido = file_get_contents($path.'JSON/'.$nombre_archivo.'.txt');
        $data = json_decode($contenido, true);
        $guia = $this->model->getGuiaCorrl($_SESSION['id_almacen'], 'G');
        $empresa = $this->model->getEmpresa();
        $registro_guia = $this->model->consultarTicketGuiaManual($data['guia_datos']['serie'], $data['guia_datos']['numero']);
        if (file_exists($path . "XML/" . $nombre_archivo . ".xml")) {
            unlink($path . "XML/" . $nombre_archivo . ".xml");
        }

        $this->crear_xml($path, $nombre_archivo, $empresa, $guia, $data['guia_datos'], $data['guia_products'], $data['destinatario']);
        $this->firmar_xml($path, $nombre_archivo, "produccion");
        $this->solicitar_token($path, $nombre_archivo, $empresa, $registro_guia['id']);
        $msg = array('msg' => 'ok', 'id_guia' => $registro_guia);

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarPorTxt()
    {
        $path = "guia_electronica/";
        $files = glob($path . "JSON/*.txt");
    
        foreach ($files as $file) {
            $txt_content = file_get_contents($file);
            $data = json_decode($txt_content, true);
    
            if (isset($data['guia_datos']['lectura']) && $data['guia_datos']['lectura'] === "true") {
                continue;
            }
            try {
                $registro_guia = $this->model->registrarGuia(
                    $data['guia_datos']['serie'],
                    $data['guia_datos']['numero'],
                    $data['guia_datos']['f_emision'],
                    $data['guia_datos']['f_traslado'],
                    $data['guia_datos']['ruc'],
                    $data['guia_datos']['razon_social'],
                    $data['guia_datos']['motivo'],
                    $data['guia_datos']['modalidad'],
                    !empty($data['guia_datos']['transporte_ruc']) ? $data['guia_datos']['transporte_ruc'] : null,
                    !empty($data['guia_datos']['transporte_razon_social']) ? $data['guia_datos']['transporte_razon_social'] : null,
                    !empty($data['guia_datos']['num_registro_mtc']) ? $data['guia_datos']['num_registro_mtc'] : null,
                    !empty($data['guia_datos']['chofer_nombres']) ? $data['guia_datos']['chofer_nombres'] : null,
                    !empty($data['guia_datos']['chofer_apellidos']) ? $data['guia_datos']['chofer_apellidos'] : null,
                    !empty($data['guia_datos']['chofer_dni']) ? $data['guia_datos']['chofer_dni'] : null,
                    !empty($data['guia_datos']['chofer_licencia']) ? $data['guia_datos']['chofer_licencia'] : null,
                    !empty($data['guia_datos']['carro_placa']) ? $data['guia_datos']['carro_placa'] : null,
                    $data['guia_datos']['distrito_llegada'],
                    $data['guia_datos']['direccion_llegada'],
                    $data['guia_datos']['distrito_partida'],
                    $data['guia_datos']['direccion_partida'],
                    !empty($data['guia_datos']['peso_bruto_total']) ? $data['guia_datos']['peso_bruto_total'] : null,
                    !empty($data['guia_datos']['numero_bultos']) ? $data['guia_datos']['numero_bultos'] : null,
                    !empty($data['destinatario']['ruc']) ? $data['destinatario']['ruc'] : null,
                    !empty($data['destinatario']['razon_social']) ? $data['destinatario']['razon_social'] : null,
                    !empty($data['destinatario']['numero_documento']) ? $data['destinatario']['numero_documento'] : null,
                    !empty($data['destinatario']['apellidoPaterno']) ? $data['destinatario']['apellidoPaterno'] : null,
                    !empty($data['destinatario']['apellidoMaterno']) ? $data['destinatario']['apellidoMaterno'] : null,
                    !empty($data['destinatario']['nombres']) ? $data['destinatario']['nombres'] : null
                );
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar la Guia: ' . $e->getMessage(), 'icon' => 'error', 'error' => 'error');
                echo json_encode($msg);
                die();
            }
    
            foreach ($data['guia_products'] as $producto) {
                try {
                    $this->model->registrarGuiaDetalle($registro_guia, $producto['cod_producto'], $producto['cantidad'], $producto['product_name']);
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar el Detalle Guia: ' . $e->getMessage(), 'icon' => 'error', 'error' => 'error');
                    echo json_encode($msg);
                    die();
                }
            }
    
            $data['guia_datos']['lectura'] = "true";
            $updated_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($file, $updated_content);
            $procesamiento_guia = $this->model->consultaTipoProceso();

            if($procesamiento_guia['proceso_guias'] == 0){
                $msg = array('msg' => 'ok', 'id_guia' => $registro_guia);
            }else if($procesamiento_guia['proceso_guias'] == 1){
                $path = "guia_electronica/";
                $guia = $this->model->getGuiaCorrl($_SESSION['id_almacen'], 'G');
                $empresa = $this->model->getEmpresa();
                $nombre_archivo = $empresa['ruc'] . '-09-' . $data['guia_datos']['serie'] . '-' . $data['guia_datos']['numero'];
        
                if (file_exists($path . "XML/" . $nombre_archivo . ".xml")) {
                    unlink($path . "XML/" . $nombre_archivo . ".xml");
                }
        
                $this->crear_xml($path, $nombre_archivo, $empresa, $guia, $data['guia_datos'], $data['guia_products'], $data['destinatario']);
                $this->firmar_xml($path, $nombre_archivo, "produccion");
                $this->solicitar_token($path, $nombre_archivo, $empresa, $registro_guia);
        
                $msg = array('msg' => 'ok', 'id_guia' => $registro_guia);
            }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            break;
        }
    }

    function crear_xml($path, $nombre_archivo, $empresa, $guia, $data_guia, $data_products_guia, $data_destinatario){
        $xml = $this->desarrollo_xml($empresa, $guia, $data_guia, $data_products_guia, $data_destinatario);
        $archivo = fopen($path."XML/".$nombre_archivo.".xml", "w+");
        fwrite($archivo, utf8_decode($xml));
        fclose($archivo);
    }

    public function solicitar_token($path, $nombre_archivo, $empresa_datos, $id_guia)
    {
        $empresa['guias_client_id']                       = 'b8dce258-5ad8-40f8-8212-51e17b55e419';
        $empresa['guias_client_secret']                   = 'WlRMhT9/JDJ1sbHOJMpdrw==';
        $empresa['ruc']                                   = 20108572109;
        $empresa['usu_secundario_produccion_user']        = 'AN9F6THA';
        $empresa['usu_secundario_produccion_password']    = 'BALEno311811';
        $token_access = $this->token($empresa['guias_client_id'], $empresa['guias_client_secret'], $empresa['ruc'].$empresa['usu_secundario_produccion_user'], $empresa['usu_secundario_produccion_password']);
        $numero_ticket = $this->envio_xml($path.'FIRMA/',$nombre_archivo, $token_access);
        sleep(2);
        $respuesta_ticket = $this->envio_ticket($path.'CDR/', $numero_ticket, $token_access, $empresa['ruc'], $nombre_archivo);
        $this->model->actualizarGuiaTicket($numero_ticket, $respuesta_ticket['ticket_rpta'], $respuesta_ticket['cdr_msj_sunat'], $respuesta_ticket['cdr_hash'], $id_guia);
    }

    function desarrollo_xml($empresa, $guia, $data_guia, $data_products_guia, $data_destinatario){        
        $xml =  '<?xml version="1.0" encoding="UTF-8"?>
            <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">                    
                    <ext:UBLExtensions>
                        <ext:UBLExtension>
                            <ext:ExtensionContent></ext:ExtensionContent>
                        </ext:UBLExtension>
                    </ext:UBLExtensions>
                    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                    <cbc:CustomizationID>2.0</cbc:CustomizationID>
                    <cbc:ID>'.$data_guia['serie'].'-'.$data_guia['numero'].'</cbc:ID>
                    <cbc:IssueDate>'.$data_guia['f_emision'].'</cbc:IssueDate>
                    <cbc:IssueTime>'.date("H:i:s").'</cbc:IssueTime>
                    <cbc:DespatchAdviceTypeCode>09</cbc:DespatchAdviceTypeCode>
                    <cac:Signature>
                      <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                      <cac:SignatoryParty>
                        <cac:PartyIdentification>
                          <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                        </cac:PartyIdentification>
                      </cac:SignatoryParty>
                      <cac:DigitalSignatureAttachment>
                        <cac:ExternalReference>
                          <cbc:URI>'.$empresa['ruc'].'</cbc:URI>
                        </cac:ExternalReference>
                      </cac:DigitalSignatureAttachment>
                    </cac:Signature>';
            $xml .= '<cac:DespatchSupplierParty>
                        <cac:Party>
                            <cac:PartyIdentification>
                                <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$empresa['ruc'].'</cbc:ID>
                            </cac:PartyIdentification>
                            <cac:PartyName>
                                <cbc:Name><![CDATA['.$empresa['nombre'].']]></cbc:Name>
                            </cac:PartyName>
                            <cac:PartyLegalEntity>
                                <cbc:RegistrationName><![CDATA['.$empresa['nombre'].']]></cbc:RegistrationName>
                            </cac:PartyLegalEntity>
                        </cac:Party>
                    </cac:DespatchSupplierParty>';
    
            $xml .= '<cac:DeliveryCustomerParty>
            <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $data_destinatario['ruc'] . '</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyLegalEntity>';
            if (empty($data_destinatario['dni']) && !empty($data_destinatario['ruc'])) {
                $xml .= '<cbc:RegistrationName><![CDATA[' . $data_destinatario['razon_social'] . ']]></cbc:RegistrationName>';
            } elseif (!empty($data_destinatario['dni']) && !empty($data_destinatario['ruc'])) {
                $xml .= '<cbc:RegistrationName><![CDATA[' . $data_destinatario['apellidoPaterno'] . ' ' . $data_destinatario['apellidoMaterno'] . ' ' . $data_destinatario['nombres'] . ']]></cbc:RegistrationName>';
            }
            $xml .= '</cac:PartyLegalEntity>
                    </cac:Party>
                </cac:DeliveryCustomerParty>';
    
            $xml .= '<cac:Shipment>
                        <cbc:ID>SUNAT_Envio</cbc:ID>
                        <cbc:HandlingCode listAgencyName="PE:SUNAT" listName="Motivo de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo20">01</cbc:HandlingCode>
                        <cbc:GrossWeightMeasure unitCode="KGM">'.$data_guia['peso_bruto_total'].'</cbc:GrossWeightMeasure>';
    
                        if($data_guia['motivo'] == 7){//importaciones
                $xml .= '<cbc:TotalTransportHandlingUnitQuantity>'.$data_guia['numero_bultos'].'</cbc:TotalTransportHandlingUnitQuantity>';
                        }
    
                $xml .= '<cac:ShipmentStage>
                            <cbc:ID>1</cbc:ID>
                            <cbc:TransportModeCode listAgencyName="PE:SUNAT" listName="Modalidad de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo18">0'.$data_guia['modalidad'].'</cbc:TransportModeCode>
                            <cac:TransitPeriod>
                                <cbc:StartDate>'.$data_guia['f_traslado'].'</cbc:StartDate>
                            </cac:TransitPeriod>';
    
                if($data_guia['modalidad'] == '1'){
                $xml .= '<cac:CarrierParty>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$data_guia['transporte_ruc'].'</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA['.$data_guia['transporte_razon_social'].']]></cbc:RegistrationName>';
                                    if($data_guia['num_registro_mtc '] != ''){
                $xml .=                 '<cbc:CompanyID>'.$data_guia['num_registro_mtc '].'</cbc:CompanyID>';
                                    }
                $xml .=         '</cac:PartyLegalEntity>
                            </cac:CarrierParty>';
                }
                if($data_guia['modalidad'] == '2'){
                $xml .= '<cac:DriverPerson>
                                <cbc:ID schemeID="1" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$data_guia['chofer_dni'].'</cbc:ID>
                                <cbc:FirstName>'.$data_guia['chofer_nombres'].'</cbc:FirstName>
                                <cbc:FamilyName>'.$data_guia['chofer_apellidos'].'</cbc:FamilyName>
                                <cbc:JobTitle>Principal</cbc:JobTitle>
                                <cac:IdentityDocumentReference>
                                    <cbc:ID>'.$data_guia['chofer_licencia'].'</cbc:ID>
                                </cac:IdentityDocumentReference>
                            </cac:DriverPerson>';                                                                        
                }
    
                $xml .= '</cac:ShipmentStage>
                        <cac:Delivery>
                            <cac:DeliveryAddress>
                                <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$data_guia['distrito_llegada'].'</cbc:ID>
                                <cac:AddressLine>
                                    <cbc:Line>'.$data_guia['direccion_llegada'].'</cbc:Line>
                                </cac:AddressLine>
                            </cac:DeliveryAddress>
                            <cac:Despatch>
                                <cac:DespatchAddress>
                                    <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$data_guia['distrito_partida'].'</cbc:ID>
                                    <cac:AddressLine>
                                        <cbc:Line>'.$data_guia['direccion_partida'].'</cbc:Line>
                                    </cac:AddressLine>
                                </cac:DespatchAddress>
                            </cac:Despatch>
                        </cac:Delivery>';
    
                        if($data_guia['modalidad'] == '2'){
                $xml .= '<cac:TransportHandlingUnit>
                            <cac:TransportEquipment>
                                <cbc:ID>'.$data_guia['carro_placa'].'</cbc:ID>
                            </cac:TransportEquipment>
                        </cac:TransportHandlingUnit>';
                        }
                $xml .= '</cac:Shipment>';        
    //aca me quede,  revisar $guia['numero_documento_transporte'] que esta en la linea 324
                    $i = 1;                        
                    foreach($data_products_guia as $values){                    
                    $xml .=  '<cac:DespatchLine>
                        <cbc:ID>'.$i.'</cbc:ID>
                        <cbc:DeliveredQuantity unitCode="'.$values['cod_producto'].'">'.$values['cantidad'].'</cbc:DeliveredQuantity>
                        <cac:OrderLineReference>
                            <cbc:LineID>1</cbc:LineID>
                        </cac:OrderLineReference>
                        <cac:Item>
                            <cbc:Description>'.$values['product_name'].'</cbc:Description>
                            <cac:SellersItemIdentification>
                            <cbc:ID>'.$values['cod_producto'].'</cbc:ID>
                            </cac:SellersItemIdentification>
                        </cac:Item>
                    </cac:DespatchLine>';                        
                    $i++;                    
                    }
            $xml.=  '</DespatchAdvice>';
        return $xml;
    }

    function firmar_xml($path, $nombre_archivo, $entorno){
        $xmlstr = file_get_contents($path."XML/".$nombre_archivo.".xml");
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($xmlstr); 
        $factura  = new Factura();    
        $xml = $factura->firmar($domDocument, '', $entorno);
        $content = $xml->saveXML();
        file_put_contents($path."FIRMA/".$nombre_archivo.".xml", $content);
        $zip = new ZipArchive();
        if($zip->open($path."FIRMA/".$nombre_archivo.".zip", ZipArchive::CREATE) === true){
            $zip->addFile($path."FIRMA/".$nombre_archivo.".xml", $nombre_archivo.".xml");
        }
    }

    public function token($client_id, $client_secret, $usuario_secundario, $usuario_password){
        $url = "https://api-seguridad.sunat.gob.pe/v1/clientessol/".$client_id."/oauth2/token/";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
    
        $datos = array(
                'grant_type'    =>  'password',     
                'scope'         =>  'https://api-cpe.sunat.gob.pe',
                'client_id'     =>  $client_id,
                'client_secret' =>  $client_secret,
                'username'      =>  $usuario_secundario,
                'password'      =>  $usuario_password
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($datos));
        curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookies.txt');
    
        $headers = array('Content-Type' => 'Application/json');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        return $response->access_token;
    }

    function envio_xml($path, $nombre_file, $token_access){
        $curl = curl_init();
        $data = array(
                    'nomArchivo'  =>  $nombre_file.".zip",
                    'arcGreZip'   =>  base64_encode(file_get_contents($path.$nombre_file.'.zip')),
                    'hashZip'     =>  hash_file("sha256", $path.$nombre_file.'.zip')
                );
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/".$nombre_file,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode(array('archivo' => $data)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '. $token_access,
                'Content-Type: application/json'
            ),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $response2 = json_decode($result);
        return $response2->numTicket;
    }
    
    function envio_ticket($ruta_archivo_cdr, $ticket, $token_access, $ruc, $nombre_file){
        if(($ticket == "") || ($ticket == null)){
            $mensaje['cdr_hash'] = '';
            $mensaje['cdr_msj_sunat'] = 'Ticket vacio';
            $mensaje['cdr_ResponseCode']  = null;
            $mensaje['numerror'] = null;
        }else{
        
            $mensaje['ticket'] = $ticket;
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/'.$ticket,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'numRucEnvia: '.$ruc,
                    'numTicket: '.$ticket,
                    'Authorization: Bearer '. $token_access,
                ),
            ));
    
            $result  = curl_exec($curl);
            curl_close($curl);
            $response3  = json_decode($result);
            $codRespuesta = $response3->codRespuesta;
            $mensaje['ticket_rpta'] = $codRespuesta;
            if($codRespuesta == '99'){
                $error = $response3->error;
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = $error->desError;
                $mensaje['cdr_ResponseCode'] = '99';
                $mensaje['numerror'] = $error->numError;            	            
            }else if($codRespuesta == '98'){
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = 'Envío en proceso';
                $mensaje['cdr_ResponseCode']  = '98';
                $mensaje['numerror'] = '98';                        
            }else if($codRespuesta == '0'){
                $mensaje['arcCdr'] = $response3->arcCdr;
                $mensaje['indCdrGenerado'] = $response3->indCdrGenerado;
                
                file_put_contents($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP', base64_decode($response3->arcCdr));
    
                $zip = new ZipArchive;
                if ($zip->open($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP') === TRUE) {
                    $zip->extractTo($ruta_archivo_cdr);
                    $zip->close();
                }
                //unlink($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP');
    
             //=============hash CDR=================
                $doc_cdr = new DOMDocument();
                $doc_cdr->load($ruta_archivo_cdr . 'R-' . $nombre_file . '.xml');
                
                $mensaje['cdr_hash']            = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $mensaje['cdr_msj_sunat']       = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                $mensaje['cdr_ResponseCode']    = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;        
                $mensaje['numerror']            = '';
            }else{
                $mensaje['cdr_hash']            = '';
                $mensaje['cdr_msj_sunat']       = 'SUNAT FUERA DE SERVICIO';
                $mensaje['cdr_ResponseCode']    = '88';            
                $mensaje['numerror']            = '88';
            }
        }
        return $mensaje;
    }
}
?>