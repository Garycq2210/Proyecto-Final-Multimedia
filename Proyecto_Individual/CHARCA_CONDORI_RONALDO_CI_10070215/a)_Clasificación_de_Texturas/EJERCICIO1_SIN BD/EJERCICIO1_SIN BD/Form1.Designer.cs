namespace EJERCICIO1_SIN_BD
{
    partial class Form1
    {
        /// <summary>
        /// Variable del diseñador requerida.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Limpiar los recursos que se estén utilizando.
        /// </summary>
        /// <param name="disposing">true si los recursos administrados se deben eliminar; false en caso contrario.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Código generado por el Diseñador de Windows Forms

        /// <summary>
        /// Método necesario para admitir el Diseñador. No se puede modificar
        /// el contenido del método con el editor de código.
        /// </summary>
        private void InitializeComponent()
        {
            this.openFileDialog1 = new System.Windows.Forms.OpenFileDialog();
            this.pictureOriginal = new System.Windows.Forms.PictureBox();
            this.btnCargar = new System.Windows.Forms.Button();
            this.btnClasificar = new System.Windows.Forms.Button();
            this.pictureResultado = new System.Windows.Forms.PictureBox();
            ((System.ComponentModel.ISupportInitialize)(this.pictureOriginal)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureResultado)).BeginInit();
            this.SuspendLayout();
            // 
            // openFileDialog1
            // 
            this.openFileDialog1.FileName = "openFileDialog1";
            // 
            // pictureOriginal
            // 
            this.pictureOriginal.Location = new System.Drawing.Point(164, 12);
            this.pictureOriginal.Name = "pictureOriginal";
            this.pictureOriginal.Size = new System.Drawing.Size(649, 571);
            this.pictureOriginal.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.pictureOriginal.TabIndex = 0;
            this.pictureOriginal.TabStop = false;
            // 
            // btnCargar
            // 
            this.btnCargar.Location = new System.Drawing.Point(25, 171);
            this.btnCargar.Name = "btnCargar";
            this.btnCargar.Size = new System.Drawing.Size(100, 45);
            this.btnCargar.TabIndex = 1;
            this.btnCargar.Text = "Cargar IMG";
            this.btnCargar.UseVisualStyleBackColor = true;
            this.btnCargar.Click += new System.EventHandler(this.btnCargar_Click);
            // 
            // btnClasificar
            // 
            this.btnClasificar.Location = new System.Drawing.Point(25, 245);
            this.btnClasificar.Name = "btnClasificar";
            this.btnClasificar.Size = new System.Drawing.Size(100, 70);
            this.btnClasificar.TabIndex = 2;
            this.btnClasificar.Text = "CLASIFICAR TEXTURAS";
            this.btnClasificar.UseVisualStyleBackColor = true;
            this.btnClasificar.Click += new System.EventHandler(this.btnClasificar_Click);
            // 
            // pictureResultado
            // 
            this.pictureResultado.Location = new System.Drawing.Point(819, 12);
            this.pictureResultado.Name = "pictureResultado";
            this.pictureResultado.Size = new System.Drawing.Size(649, 571);
            this.pictureResultado.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.pictureResultado.TabIndex = 5;
            this.pictureResultado.TabStop = false;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(1496, 595);
            this.Controls.Add(this.pictureResultado);
            this.Controls.Add(this.btnClasificar);
            this.Controls.Add(this.btnCargar);
            this.Controls.Add(this.pictureOriginal);
            this.Name = "Form1";
            this.Text = "Form1";
            ((System.ComponentModel.ISupportInitialize)(this.pictureOriginal)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureResultado)).EndInit();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.OpenFileDialog openFileDialog1;
        private System.Windows.Forms.PictureBox pictureOriginal;
        private System.Windows.Forms.Button btnCargar;
        private System.Windows.Forms.Button btnClasificar;
        private System.Windows.Forms.PictureBox pictureResultado;
    }
}

