import asyncio
import websockets
import json
import requests  # Para la autenticación HTTP
import time

class ESP8266Simulator:
    def __init__(self):
        self.uri = "ws://192.168.164.200:8080/app/njy8gii5tqlnr8ofoxz3"
        self.channel = "private-rfid.register"
        self.auth_url = "http://192.168.164.200:8001/api/v1/broadcasting/auth"  # Ajusta esto si estás en producción
        self.headers = {
            "Authorization": "Bearer 1|dPDQJy2hNpU7vaCEZK4sdan5WjUZ7xdrgKd1VrG5eb80cf1b",  # Reemplaza por el token válido
            "Accept": "application/json"
        }

    async def connect(self):
        try:
            async with websockets.connect(self.uri) as websocket:
                print(f"Conectado a {self.uri}")

                # Obtener socket_id
                raw = await websocket.recv()
                data = json.loads(raw)
                payload = json.loads(data['data'])
                socket_id = payload['socket_id']
                print(f"Conexión establecida. socket_id: {socket_id}")

                # Autenticarse
                auth_response = requests.post(self.auth_url, data={
                    "socket_id": socket_id,
                    "channel_name": self.channel
                }, headers=self.headers)

                if auth_response.status_code != 200:
                    print("Fallo en autenticación del canal:", auth_response.text)
                    return

                auth_data = auth_response.json()
                print("Auth:", auth_data)  

                # Enviar suscripción con auth
                subscribe_message = {
                    "event": "pusher:subscribe",
                    "data": {
                        "channel": self.channel,
                        "auth": auth_data["auth"]
                    }
                }
                await websocket.send(json.dumps(subscribe_message))
                print(f"Suscrito al canal: {self.channel}")

                # Escuchar mensajes
                await self.receive_messages(websocket)

        except Exception as e:
            print(f"Error: {e}")

    async def receive_messages(self, websocket):
        while True:
            try:
                msg = await websocket.recv()
                print("Mensaje recibido:", msg)
            except Exception as e:
                print(f"Error recibiendo mensaje: {e}")
                break

async def main():
    sim = ESP8266Simulator()
    await sim.connect()

if __name__ == "__main__":
    asyncio.run(main())
