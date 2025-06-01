import asyncio
import websockets
import json
import time
import random


""" 
ws://localhost:8080/app/{REVERB_APP_KEY}

REVERB_APP_KEY : Tu clave de aplicació Reverb ubicada en el archivo .env de tu proyecto Laravel.



import requests

headers = {
    "Authorization": "Bearer 1|dPDQJy2hNpU7vaCEZK4sdan5WjUZ7xdrgKd1VrG5eb80cf1b",
    "Content-Type": "application/json",
    "Accept": "application/json"
}

payload = {
    "channel_name": "rfid-read"
}

response = requests.post("http://localhost:8001/broadcasting/auth", json=payload, headers=headers)

print(response)
"""









class ESP8266Simulator:
    def __init__(self):
        # Configuración del WebSocket
        #self.uri = "wss://api.smartunlocker.site/app/jbwlrcnx3u1hz1qglfyj"
        #self.uri = "wss://api.smartunlocker.site/app/jbwlrcnx3u1hz1qglfyj"
        self.uri = "ws://localhost:8080/app/njy8gii5tqlnr8ofoxz3"
        self.channel = " Access.Request."
        self.connected = False

    async def connect(self):
        #Establece la conexión WebSocket y maneja los mensajes
        try:
            async with websockets.connect(self.uri) as websocket:
                print(f"Conectado a {self.uri}")
                self.connected = True

                # Enviar mensaje de suscripción
                subscribe_message = {
                    "event": "pusher:subscribe",
                    "data": {
                        "channel": self.channel,
                    }
                }
                await websocket.send(json.dumps(subscribe_message))
                print(f"Suscrito al canal: {self.channel}")

                # Iniciar las tareas de envío y recepción
                await asyncio.gather(
                    self.send_periodic_data(websocket),
                    self.receive_messages(websocket)
                )
        except Exception as e:
            print(f"Error de conexión: {e}")
            self.connected = False

    async def send_periodic_data(self, websocket):
        #Simula el envío periódico de datos como lo haría el ESP8266
        while True:
            try:
                
                mensaje = {
                    "event" : "pusher:ping",
                    "data" : {},
                }
                
                await websocket.send(json.dumps(mensaje))
                print(f"Mensaje enviado: {mensaje}")
                await asyncio.sleep(10)  # Espera 5 segundos entre mensajes
            except Exception as e:
                print(f"Error enviando datos: {e}")
                break
            
    def generate_rfid_uid(self):
        #Genera un UID de RFID simulado 
        return ''.join(random.choices('0123456789ABCDEF', k=8))

    async def receive_messages(self, websocket):
        #Maneja los mensajes recibidos del servidor 
        while True:
            try:
                message = await websocket.recv()
                print(f"Mensaje recibido: {message}")
                
                # Procesar el mensaje JSON recibido
                data = json.loads(message)
                if data.get("event") == "pusher:connection_established":
                    print("Conexión establecida con el servidor")
                    
                    connection_data = json.loads(data["data"])
                    socket_id = connection_data["socket_id"]
                    print(f"Conexión establecida. socket_id: {socket_id}")
                    
                    
                elif data.get("event") == "pusher:subscription_succeeded":
                    print(f"Suscripción exitosa al canal {self.channel}")
            except Exception as e:
                print(f"Error recibiendo mensajes: {e}")
                break

async def main():
    # Crear y ejecutar el simulador
    simulator = ESP8266Simulator()
    
    try:
        print("Iniciando simulador ESP8266...")
        await simulator.connect()
        
    except KeyboardInterrupt:
        print("\nSimulador detenido por el usuario")
    except Exception as e:
        print(f"Error en el simulador: {e}")

if __name__ == "__main__":
    # Configurar logging para ver más detalles (opcional)
    import logging
    logging.basicConfig(level=logging.INFO)
    
    # Ejecutar el simulador
    asyncio.run(main())  