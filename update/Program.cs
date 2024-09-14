using MySql.Data.MySqlClient;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using Org.BouncyCastle.Utilities.Collections;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;
using Flurl;
using Flurl.Http;
using System.Net;
using MySql.Data;
using System.Collections;
using System.Threading;

namespace Update
{
    internal class Program
    {
        static async Task Main(string[] args)
        {
            ServicePointManager.ServerCertificateValidationCallback += (sender, certificate, chain, sslPolicyErrors) => true;

            MySqlConnection Connection = new MySqlConnection("Server='localhost'; database='vidaxl2etsy'; UID='root'; password=''");
            Connection.Open();

            int limit = 300;
            int total = limit;
            int offset = 0;
            while (total > offset)
            {
                var json = await $"https://b2b.vidaxl.com/api_customer/products?limit={limit}&offset={offset}".WithHeader("Authorization", "Basic c2hvcGp1c3RsaXR0bGVtaXNzQGdtYWlsLmNvbTo1MTdjNWRhZi1kOGYxLTQ0MDktYWZmZS0wNmI1NTU3Y2FjNmQ=").GetJsonAsync();
                var data = json.data;
                var pagination = json.pagination;
                total = (int)pagination.total;
                offset += limit;

                int i = 0;
                string query = "REPLACE INTO vidaxl_products(product_id, name, code, category_path, quantity, price, currency, created_at, updated_at) VALUES";
                foreach (var item in data)
                {
                    long product_id = Convert.ToInt64(item.id);
                    string name = Convert.ToString(item.name);
                    string code = Convert.ToString(item.code);
                    string category_path = Convert.ToString(item.category_path);
                    string quantity = Convert.ToString(item.quantity);
                    string price = Convert.ToString(item.price);
                    string currency = Convert.ToString(item.currency);
                    string created_at = Convert.ToString(item.created_at);
                    string updated_at = Convert.ToString(item.updated_at);
                    if (i > 0) { query += ", "; }
                    query += $"('{product_id.ToString()}', '{name.Replace("'","\\'")}', '{code}', '{category_path.Replace("'", "\\'")}', '{quantity}', '{price}', '{currency}', '{created_at}', '{updated_at}')";
                    i++;
                }

                var cmd = new MySqlCommand(query, Connection);
                cmd.ExecuteNonQuery();
                Console.Clear();
                Console.WriteLine(offset * 100 / total);
                Thread.Sleep(5000);
            }
            
            Connection.Close();
        }
    }
}
