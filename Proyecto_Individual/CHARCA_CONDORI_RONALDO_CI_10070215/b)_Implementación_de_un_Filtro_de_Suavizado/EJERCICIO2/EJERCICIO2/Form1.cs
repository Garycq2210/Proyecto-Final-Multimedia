using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace EJERCICIO2
{
    public partial class Form1 : Form
    {
        Bitmap imagenOriginal;
        Bitmap imagenSuavizada;
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            OpenFileDialog img = new OpenFileDialog();
            img.Filter = "Imagenes|*.jpg;*.png;*.bmp";

            if (img.ShowDialog() == DialogResult.OK)
            {
                imagenOriginal = new Bitmap(img.FileName);
                pictureBox1.Image = imagenOriginal;
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (imagenOriginal == null)
            {
                MessageBox.Show("Primero cargue una imagen.");
                return;
            }

            Bitmap temp = new Bitmap(imagenOriginal);

            temp = SuavizarImagen(temp);
            temp = SuavizarImagen(temp);
            temp = SuavizarImagen(temp);
            temp = SuavizarImagen(temp);

            imagenSuavizada = temp;
            pictureBox2.Image = imagenSuavizada;

        }
        private Bitmap SuavizarImagen(Bitmap original)
        {
            Bitmap nueva = new Bitmap(original.Width, original.Height);

            for (int y = 1; y < original.Height - 1; y++)
            {
                for (int x = 1; x < original.Width - 1; x++)
                {
                    int sumaR = 0;
                    int sumaG = 0;
                    int sumaB = 0;

                    for (int j = -1; j <= 1; j++)
                    {
                        for (int i = -1; i <= 1; i++)
                        {
                            Color pixel = original.GetPixel(x + i, y + j);

                            sumaR += pixel.R;
                            sumaG += pixel.G;
                            sumaB += pixel.B;
                        }
                    }

                    int r = sumaR / 9;
                    int g = sumaG / 9;
                    int b = sumaB / 9;

                    nueva.SetPixel(x, y, Color.FromArgb(r, g, b));
                }
            }

            return nueva;
        }
    }
}
