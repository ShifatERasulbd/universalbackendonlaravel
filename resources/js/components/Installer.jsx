import React, { useEffect, useState } from "react";
import axios from "axios";

export default function Installer() {
    const [categories,setcategories]=useState([]);
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        phone: "",
        business_name: "",
        business_url: "",
        business_category: ""
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await axios.post(
                "/api/installer/step-one",
                formData
            );

            if (response.data.status) {
                // move to next step
                window.location.href = "/installer/theme";
            }

        } catch (error) {

            console.error(error.response?.data || error.message);

        }
    };

    useEffect(()=>{
        axios.get("/api/installer/business-categories").then((response)=>{
            setcategories(response.data);
        })
         .catch((error) => {
            console.error(error);
        });
    },[]);

    return (
        <div style={styles.wrapper}>

            <div style={styles.card}>

                <h2 style={styles.title}>
                    Setup Your Business 🚀
                </h2>

                <form onSubmit={handleSubmit}>

                    <div style={styles.grid}>

                        <div style={styles.field}>
                            <label style={styles.label}>Your Name</label>
                            <input
                                name="name"
                                placeholder="John Doe"
                                value={formData.name}
                                onChange={handleChange}
                                style={styles.input}
                            />
                        </div>

                        <div style={styles.field}>
                            <label style={styles.label}>Email Address</label>
                            <input
                                name="email"
                                placeholder="john@email.com"
                                value={formData.email}
                                onChange={handleChange}
                                style={styles.input}
                            />
                        </div>

                        <div style={styles.field}>
                            <label style={styles.label}>Phone Number</label>
                            <input
                                name="phone"
                                placeholder="+8801XXXXXXXXX"
                                value={formData.phone}
                                onChange={handleChange}
                                style={styles.input}
                            />
                        </div>

                        <div style={styles.field}>
                            <label style={styles.label}>Business Name</label>
                            <input
                                name="business_name"
                                placeholder="Your Company Ltd."
                                value={formData.business_name}
                                onChange={handleChange}
                                style={styles.input}
                            />
                        </div>

                        <div style={styles.field}>
                            <label style={styles.label}>Business Website</label>
                            <input
                                name="business_url"
                                placeholder="https://example.com"
                                value={formData.business_url}
                                onChange={handleChange}
                                style={styles.input}
                            />
                        </div>

                        <div style={styles.field}>
                            <label style={styles.label}>Business Category</label>
                            <select
                                name="business_category"
                                value={formData.business_category}
                                onChange={handleChange}
                                style={styles.input}
                            >
                                <option value="">Select Category</option>
                                {categories.map((category)=>(
                                  <option key={category.id} value={category.id}>{category.name}</option>
                                ))}
                            </select>
                        </div>

                    </div>

                    <button style={styles.button}>
                        Continue Installation →
                    </button>

                </form>

            </div>

        </div>
    );
}

const styles = {

    wrapper: {
        minHeight: "100vh", // fixes scrollbar issue
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        background: "#f5f7fb",
        padding: "20px",
        overflow: "hidden"
    },

    card: {
        background: "#fff",
        padding: "40px",
        borderRadius: "14px",
        width: "720px",
        boxShadow: "0 10px 35px rgba(0,0,0,0.08)"
    },

    title: {
        textAlign: "center",
        marginBottom: "30px",
        fontSize: "26px",
        fontWeight: "700"
    },

    grid: {
        display: "grid",
        gridTemplateColumns: "1fr 1fr",
        columnGap: "30px",
        rowGap: "22px"
    },

    field: {
        display: "flex",
        flexDirection: "column",
        gap: "8px"
    },

    label: {
        fontWeight: "600",
        fontSize: "14px"
    },

    input: {
        padding: "14px",
        borderRadius: "8px",
        border: "1px solid #dcdfe6",
        fontSize: "15px"
    },

    button: {
        marginTop: "30px",
        width: "100%",
        padding: "15px",
        borderRadius: "10px",
        border: "none",
        background: "#3461ff",
        color: "#fff",
        fontWeight: "600",
        fontSize: "16px",
        cursor: "pointer"
    },

    imageWrapper: {
        marginTop: "30px",
        textAlign: "center"
    },

    image: {
        width: "220px",
        opacity: "0.9"
    }
};