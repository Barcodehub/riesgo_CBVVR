import socket

def start_test_server():
    host = "0.0.0.0"  # Escucha en todas las interfaces
    port = 1234

    # Crear un socket
    server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_socket.bind((host, port))
    server_socket.listen(1) #mensaje USERID 

    print(f"Servidor de pruebas escuchando en {host}:{port}...")

    while True:
        conn, addr = server_socket.accept()
        print(f"Conexión establecida con {addr}")

        try:
            # Recibir datos del cliente
            data = conn.recv(1024).decode().strip()  # Elimina espacios y saltos de línea
            print(f"Mensaje recibido: {data}")

            if data.isdigit():  # Verifica que sea un número
                # Responder con el mismo ID recibido (simulando éxito)
                response = f"{data}\n"  # Mantener el formato con \n
                conn.send(response.encode())
                print(f"Respondiendo con: {response.strip()}")
            else:
                # Responder con error si no es un número válido
                conn.send("ERROR: ID inválido\n".encode())
                
        except Exception as e:
            print(f"Error: {str(e)}")
            conn.send("ERROR: Problema en el servidor\n".encode())
        finally:
            conn.close()

if __name__ == "__main__":
    start_test_server()